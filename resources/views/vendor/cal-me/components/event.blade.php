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

    $resourceKeys = !empty($resourcesForEventDay) ? array_keys($resourcesForEventDay) : [];
    $currentResourceIndexInDay = !empty($resourceKeys) ? array_search($event->{$resourceAttr} ?? null, $resourceKeys) : false;
    $resourceIndex = ($currentResourceIndexInDay !== false) ? $currentResourceIndexInDay : 0;

    $previousRowsOrColsCount = 0;
    if(isset($this->getDaysRange)) {
        foreach ($this->getDaysRange as $dayInRange) {
            if ($dayInRange->isSameDay($event->from_time)) {
                break;
            }
            $dayStr = $dayInRange->format('Y-m-d');
            $resourcesForThisDay = $resources[$dayStr] ?? [];
            $count = count($resourcesForThisDay);
            $previousRowsOrColsCount += max(1, $count);
        }
    }

    $finalIndex = $previousRowsOrColsCount + $resourceIndex;
    $resourceCountForDay = !empty($resourcesForEventDay) ? count($resourcesForEventDay) : 1;

    $canResize = !isset($event->parent_event_id) || (isset($event->parent_event_id) && $event->is_last_in_group);
    $colorInfo = $this->processEventColor($event->color);

    $cellWidth = $this->getCellWidth();
    $cellWidthMobile = $this->getCellWidthMobile();
    $cellHeight = $this->getCellHeight();
    $minuteInPxVertical = $cellWidth / 60;
    $minuteInPxHorizontal = $cellHeight / 60;
    $quarterIntervalVertical = $cellWidth / 4;
    $quarterIntervalHorizontal = $cellHeight / 4;

    $fromHour = $this->getFromHour();
    $minutesFromDayStart = $event->from_time->copy()->setHour($fromHour)->setMinute(0)->diffInMinutes($event->from_time);
    $eventDurationMinutes = $event->from_time->diffInMinutes($event->to_time);

    $labelFrom = isset($event->original_event_from_time)
        ? $event->original_event_from_time->format("d.m H:i")
        : $event->from_time?->format("H:i");
    $labelTo = isset($event->original_event_to_time)
        ? $event->original_event_to_time->format("d.m H:i")
        : $event->to_time?->format("H:i");

    $hasCapacity = isset($event->capacity) && $event->capacity > 0;
    $bookedCount = $event->booked_count ?? 0;
    $capacityPercent = $hasCapacity ? min(100, ($bookedCount / $event->capacity) * 100) : 0;

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

    $guestName = $event->guest_name ?? null;
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
    style="height: {{ $vertical ? $cellHeight : 'auto' }}px; {{ isset($event->style) ? $event->style . ';' : '' }}"
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
        <div class="cal-event-meta flex items-center gap-1">
            <small class="flex-1" x-text="label"></small>
            @if($statusIcon)
                <x-filament::icon :icon="$statusIcon" class="cal-event-meta-icon" />
            @endif
        </div>

        @if($guestName)
            <div class="mt-0.5">
                <span class="cal-event-badge truncate">
                    {{ $guestName }}
                </span>
            </div>
        @endif

        @if($hasCapacity)
            <div class="cal-event-capacity mt-auto">
                <x-filament::icon icon="heroicon-o-users" class="cal-event-meta-icon" />
                <span>{{ $bookedCount }}/{{ $event->capacity }}</span>
            </div>
        @endif
    </div>

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
