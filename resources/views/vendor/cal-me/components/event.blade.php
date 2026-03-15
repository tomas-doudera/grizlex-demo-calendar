@props([
    'event',
    'resources',
    'vertical' => true,
    'singleDay' => false,
])

@php
    $canUpdate = $event->updatable ?? $this->canUpdate($event->id);
    $canEdit = $event->editable ?? $this->canEdit($event->id);
    $eventDay = $event->from_time->format('Y-m-d');
    $resourceAttr = $this->getResourceAttribute();
    $resourcesForEventDay = $resources[$eventDay] ?? [];

    // Resource index within the event's day (0 if the day has no resources or the event has no assigned resource)
    $resourceKeys = !empty($resourcesForEventDay) ? array_keys($resourcesForEventDay) : [];
    $currentResourceIndexInDay = !empty($resourceKeys) ? array_search($event->{$resourceAttr} ?? null, $resourceKeys) : false;
    $resourceIndex = ($currentResourceIndexInDay !== false) ? $currentResourceIndexInDay : 0;

    // Calculate the number of rows/columns occupied by preceding days in the displayed range
    $previousRowsOrColsCount = 0;
    if(isset($this->getDaysRange)) { // Check if getDaysRange is available
        foreach ($this->getDaysRange as $dayInRange) {
            if ($dayInRange->isSameDay($event->from_time)) {
                break; // Stop when we reach the event's day
            }
            $dayStr = $dayInRange->format('Y-m-d');
            $resourcesForThisDay = $resources[$dayStr] ?? [];
            $count = count($resourcesForThisDay);
            $previousRowsOrColsCount += max(1, $count); // Each day occupies at least 1 row/column
        }
    }

    // The final index (row/column) is the sum of preceding ones + the index within the current day
    $finalIndex = $previousRowsOrColsCount + $resourceIndex;

    // Number of resources for the current day (minimum 1 for width/height calculations)
    $resourceCountForDay = !empty($resourcesForEventDay) ? count($resourcesForEventDay) : 1;

    $canResize = !isset($event->parent_event_id) || (isset($event->parent_event_id) && $event->is_last_in_group);
    $colorInfo = $this->processEventColor($event->color);

    // Get cell dimensions from configuration
    $cellWidth = $this->getCellWidth();
    $cellWidthMobile = $this->getCellWidthMobile();
    $cellHeight = $this->getCellHeight();
    $minuteInPxVertical = $cellWidth / 60;
    $minuteInPxHorizontal = $cellHeight / 60;
    $quarterIntervalVertical = $cellWidth / 4;
    $quarterIntervalHorizontal = $cellHeight / 4;

    // Calculate values needed for JS
    $fromHour = $this->getFromHour();
    $minutesFromDayStart = $event->from_time->copy()->setHour($fromHour)->setMinute(0)->diffInMinutes($event->from_time);
    $eventDurationMinutes = $event->from_time->diffInMinutes($event->to_time);

    $labelFrom = isset($event->original_event_from_time)
        ? $event->original_event_from_time->format("d.m H:i")
        : $event->from_time?->format("H:i");
    $labelTo = isset($event->original_event_to_time)
        ? $event->original_event_to_time->format("d.m H:i")
        : $event->to_time?->format("H:i");

    // Capacity
    $hasCapacity = isset($event->capacity) && $event->capacity > 0;
    $bookedCount = $event->booked_count ?? 0;
    $capacityPercent = $hasCapacity ? min(100, ($bookedCount / $event->capacity) * 100) : 0;

    // Status
    $statusLabel = null;
    $statusIcon = null;
    if (isset($event->status) && $event->status) {
        $statusLabel = is_object($event->status) && method_exists($event->status, 'getLabel')
            ? $event->status->getLabel()
            : (string) $event->status;
        $statusIcon = is_object($event->status) && method_exists($event->status, 'getIcon')
            ? $event->status->getIcon()
            : null;
    }

    // Service name + color
    $serviceName = (isset($event->service) && $event->service) ? $event->service->name : null;
    $serviceColor = (isset($event->service) && $event->service) ? ($event->service->color ?? null) : null;

    // Instructor / staff name + color
    $staffName = (isset($event->staff) && $event->staff)
        ? trim(($event->staff->first_name ?? '') . ' ' . ($event->staff->last_name ?? ''))
        : ($event->guest_name ?? null);
    $staffColor = (isset($event->staff) && $event->staff) ? ($event->staff->color ?? null) : null;

    // Avatar mode
    $showAvatarMode = property_exists($this, 'showStaffAvatarsInEvents') && $this->showStaffAvatarsInEvents;
    $staffAvatarUrl = null;
    $staffInitials = null;
    if ($showAvatarMode && isset($event->staff) && $event->staff) {
        $staffAvatarUrl = method_exists($this, 'resolveStaffAvatarUrl')
            ? $this->resolveStaffAvatarUrl($event->staff->avatar_url)
            : $event->staff->avatar_url;
        $staffInitials = mb_strtoupper(mb_substr($event->staff->first_name ?? '', 0, 1) . mb_substr($event->staff->last_name ?? '', 0, 1));
    }

    // In avatar mode, use staff color as event color; fall back to service color
    if ($showAvatarMode) {
        $avatarModeColor = $staffColor ?? $event->color;
        $colorInfo = $this->processEventColor($avatarModeColor);
    }
@endphp

<div
    wire:key="{{ rand() }}"
    id="event{{ $event->id }}"
    x-ref="event-{{ $event->id }}"
    data-parent-event-id="{{ $event->parent_event_id ?? '' }}"
    @class([
        'event cal-event',
        'cursor-pointer' => $canUpdate || $canEdit,
        'opacity-70' => ! ($canUpdate || $canEdit),
        $event->className ?? ''
    ])
    x-bind:class="{ 'cal-event--resizing': resizing }"
    style="height: {{ $vertical ? $cellHeight : 'auto' }}px; {{ $showAvatarMode ? '--cal-event-color: ' . ($avatarModeColor ?? '#3b82f6') . ';' : (isset($event->style) ? $event->style . ';' : '') }}"
    x-data="calMeEvent({
        vertical: @js($vertical),
        singleDay: @js($singleDay),
        minuteInPx: @js($vertical ? $minuteInPxVertical : $minuteInPxHorizontal),
        cellHeight: @js($vertical ? $cellHeight : $quarterIntervalHorizontal),
        cellWidth: @js($vertical ? $quarterIntervalVertical : $cellWidth),
        eventId: {{ $event->id }},
        day: '{{ $event->from_time->toDateString() }}',
        resourceId: '{{ $event->{$resourceAttr} ?? '' }}',
        parentEventId: '{{ $event->parent_event_id ?? '' }}',
        canResize: @js($canResize),
        canUpdate: @js($canUpdate),
        labelFrom: '{{ $labelFrom }}',
        labelTo: '{{ $labelTo }}',
        minuteInPxVertical: @js($minuteInPxVertical),
        minuteInPxHorizontal: @js($minuteInPxHorizontal),
        cellHeightConfig: @js($cellHeight),
        cellWidthConfig: @js($cellWidth),
        cellWidthMobile: @js($cellWidthMobile),
        quarterIntervalVertical: @js($quarterIntervalVertical),
        quarterIntervalHorizontal: @js($quarterIntervalHorizontal),
        finalIndex: {{ $finalIndex }},
        resourceIndex: {{ $resourceIndex }},
        resourceCountForDay: {{ $resourceCountForDay }},
        fromHour: {{ $fromHour }},
        eventDurationMinutes: {{ $eventDurationMinutes }},
        minutesFromDayStart: {{ $minutesFromDayStart }},
        originalHour: {{ $event->from_time->format('H') }},
        originalMinute: {{ $event->from_time->format('i') }},
        fromTimeTimestamp: {{ $event->from_time->timestamp }}
    })"
    x-on:resize.window.debounce.300ms="init()"
    @if($canUpdate)
    x-on:mousedown.self.debounce="onMouseDown($event)"
    x-on:touchstart.self="onTouchStart($event)"
    x-on:close-context-menu.window="closeContextMenu()"
    x-on:click.away="closeContextMenu()"
    x-on:fullscreen-changed.window="setTimeout(() => { initializeGrid() }, 100)"
    x-on:scroll.passive.window="closeContextMenu()"
    x-on:mousemove.window="onMouseMove($event)"
    x-on:mouseup.window.debounce.150ms="onMouseUp()"
    x-on:touchmove="onTouchMove($event)"
    x-on:touchend.window.debounce="onTouchEnd()"
    x-on:touchend.window="stopDragging()"
    @endif

    @if($canEdit)
        x-on:click.prevent="
            if (! resizing && ! dragging) {
                modalOpen = true;
                $wire.mountAction('editEventAction', @js(['event_id' => $event->id]))
            }
        "
    @elseIf($event?->url)
        x-on:click.prevent="window.location.href = '{{ $event?->url }}';"
    @endif
>
    <div
        class="cal-event-content flex flex-col"
        style="background-color: {{ $colorInfo['bgColor'] ?? '#eee' }}"
    >
        @if($showAvatarMode)
            {{-- Avatar mode layout --}}
            <div class="flex gap-1">
                <div class="flex-1 min-w-0">
                    <div class="cal-event-meta flex items-center gap-1">
                        <small class="flex-1" x-text="label"></small>
                    </div>
                    @if($serviceName)
                        <span
                            class="inline-flex items-center gap-1 rounded-full truncate max-w-full"
                            style="font-size: 9px; line-height: 1; font-weight: 600; padding: 2px 5px; background-color: {{ $serviceColor ?? '#6b7280' }}22; color: {{ $serviceColor ?? '#6b7280' }};"
                        ><span class="rounded-full flex-shrink-0" style="width: 5px; height: 5px; background-color: {{ $serviceColor ?? '#6b7280' }};"></span>{{ $serviceName }}</span>
                    @endif
                </div>
                @if($staffAvatarUrl || $staffInitials)
                    <div class="flex-shrink-0" style="padding-top: 1px;">
                        @if($staffAvatarUrl)
                            <img
                                src="{{ $staffAvatarUrl }}"
                                alt="{{ $staffName }}"
                                class="rounded-full object-cover"
                                style="width: 20px; height: 20px; box-shadow: 0 0 0 2px {{ $staffColor ?? '#a1a1aa' }};"
                            />
                        @else
                            <span
                                class="rounded-full flex items-center justify-center text-white font-bold"
                                style="width: 20px; height: 20px; font-size: 8px; background-color: {{ $staffColor ?? '#a1a1aa' }};"
                            >{{ $staffInitials }}</span>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Bottom row: capacity left, status icon right --}}
            @if($hasCapacity || $statusIcon)
                <div class="flex items-center mt-auto">
                    @if($hasCapacity)
                        <div class="cal-event-capacity">
                            <x-filament::icon icon="heroicon-o-users" class="cal-event-meta-icon" />
                            <span>{{ $bookedCount }}/{{ $event->capacity }}</span>
                        </div>
                    @endif
                    @if($statusIcon)
                        <div class="ml-auto">
                            <x-filament::icon :icon="$statusIcon" class="cal-event-meta-icon" />
                        </div>
                    @endif
                </div>
            @endif
        @else
            {{-- Default mode: text-based staff badge --}}
            <div class="cal-event-meta flex items-center gap-1">
                <small class="flex-1" x-text="label"></small>
                @if($statusIcon)
                    <x-filament::icon :icon="$statusIcon" class="cal-event-meta-icon" />
                @endif
            </div>

            @if($serviceName)
                <p class="cal-event-title truncate">{{ $serviceName }}</p>
            @endif

            @if($staffName)
                <div class="mt-0.5">
                    <span
                        class="cal-event-badge truncate"
                        @if($staffColor)
                            style="--staff-color: {{ $staffColor }}"
                        @endif
                    >
                        @if($staffColor)
                            <span class="cal-event-badge-dot" style="background-color: {{ $staffColor }}"></span>
                        @endif
                        {{ $staffName }}
                    </span>
                </div>
            @endif

            @if($hasCapacity)
                <div class="cal-event-capacity mt-auto">
                    <x-filament::icon icon="heroicon-o-users" class="cal-event-meta-icon" />
                    <span>{{ $bookedCount }}/{{ $event->capacity }}</span>
                </div>
            @endif
        @endif
    </div>

    <!-- Resize Handle on the Right Side -->
    @if($canUpdate)
        <div
            x-bind:class="{ 'hidden': !canResize, 'cal-event-resize--active': resizing }"
            @class([
              'resize-handle cal-event-resize',
              'cal-event-resize--v' => $vertical,
              'cal-event-resize--h' => !$vertical,
            ])
            x-on:pointerdown="onResizePointerDown($event)"
            x-on:pointermove.window.prevent="onResizePointerMove($event)"
            x-on:pointerup.window="onResizePointerUp($event)"
        ></div>
    @endif
</div>
