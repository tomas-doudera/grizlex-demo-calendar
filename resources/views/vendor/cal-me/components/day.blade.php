@props([
    'day',
    'resources' => [],
    'vertical' => true,
    'alt' => false,
])

@php
    $cellWidth = $this->getCellWidth();
    $cellWidthMobile = $this->getCellWidthMobile();
    $cellHeight = $this->getCellHeight();
    $resourceMeta = method_exists($this, 'getResourceMeta') ? $this->getResourceMeta : [];
@endphp

@if($vertical)
    <div @class(['cal-day-v', 'cal-day-alt' => $alt])>
        <div class="cal-day-v-info items-center justify-center" style="min-height: {{ $cellHeight }}px;">
            <div class="text-sm uppercase tracking-wider font-medium text-zinc-500">
                {{ $day->getTranslatedMinDayName() }}
            </div>
            <div class="text-[11px] font-semibold tabular-nums text-zinc-500">
                {{ $day->format('d.m.') }}
            </div>
        </div>
        @if(!empty($resources))
            <div class="cal-day-v-resources">
                @foreach($resources as $resourceId => $resource)
                    <div class="cal-day-v-resource" style="height: {{ $cellHeight }}px;">
                        @php $meta = $resourceMeta[$resourceId] ?? null; @endphp
                        @if($meta)
                            <div class="flex items-center gap-1.5 overflow-hidden">
                                @if($meta['avatar_url'])
                                    <img
                                        src="{{ $meta['avatar_url'] }}"
                                        alt="{{ $resource }}"
                                        style="width: 24px; height: 24px; min-width: 24px;"
                                        class="rounded-full object-cover"
                                    />
                                @else
                                    <span
                                        class="rounded-full flex items-center justify-center text-[10px] font-bold text-white"
                                        style="width: 24px; height: 24px; min-width: 24px; background-color: {{ $meta['color'] ?? '#a1a1aa' }}"
                                    >{{ $meta['initials'] ?? mb_strtoupper(mb_substr($resource, 0, 1)) }}</span>
                                @endif
                                <span class="text-xs font-medium text-zinc-500 truncate">{{ $resource }}</span>
                            </div>
                        @else
                            <span class="text-xs font-medium text-zinc-500 align-self-center truncate">{{ $resource }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@else
    <div @class([
        "cal-day-h",
        "w-full" => $this->calendarView === 'day-horizontal'
    ])>
        <div @class(['flex flex-col w-full', 'cal-day-alt' => $alt])>
            <div
                @class([
                    'cal-day-h-header',
                    'h-[40px]' => !empty($resources),
                    'h-[80px]' => empty($resources) && $this->calendarView === 'day-horizontal',
                ])
                @if(empty($resources) && $this->calendarView !== 'day-horizontal')
                    x-data="{ minWidth: window.innerWidth >= 768 ? {{ $cellWidth }} : {{ $cellWidthMobile }} }"
                    x-init="minWidth = window.innerWidth >= 768 ? {{ $cellWidth }} : {{ $cellWidthMobile }}"
                    x-on:resize.window="minWidth = window.innerWidth >= 768 ? {{ $cellWidth }} : {{ $cellWidthMobile }}"
                    x-bind:style="`height: 80px; min-width: ${minWidth}px;`"
                @endif
            >
                <div class="flex items-center justify-center gap-1.5 p-2">
                    <span class="text-sm uppercase tracking-wider font-medium text-zinc-500">{{ $day->getTranslatedMinDayName() }}</span>
                    <span class="text-[11px] font-semibold tabular-nums text-zinc-500">{{ $day->format('d.m.') }}</span>
                </div>
            </div>
            @if(!empty($resources))
                <div @class([
                    "flex flex-row flex-shrink-0 relative",
                    "w-full" => $this->calendarView === 'day-horizontal'
                ])>
                    @foreach($resources as $resourceId => $resource)
                        <div
                            @class([
                                "cal-day-h-resource",
                                "w-full" => $this->calendarView === 'day-horizontal',
                            ])
                            @if($this->calendarView !== 'day-horizontal')
                                x-data="{ cellWidth: window.innerWidth >= 768 ? {{ $cellWidth }} : {{ $cellWidthMobile }} }"
                                x-init="cellWidth = window.innerWidth >= 768 ? {{ $cellWidth }} : {{ $cellWidthMobile }}"
                                x-on:resize.window="cellWidth = window.innerWidth >= 768 ? {{ $cellWidth }} : {{ $cellWidthMobile }}"
                                x-bind:style="`width: ${cellWidth}px;`"
                            @endif
                        >
                            @php $meta = $resourceMeta[$resourceId] ?? null; @endphp
                            @if($meta)
                                <div class="flex items-center justify-center gap-1.5 px-1 overflow-hidden">
                                    @if($meta['avatar_url'])
                                        <img
                                            src="{{ $meta['avatar_url'] }}"
                                            alt="{{ $resource }}"
                                            style="width: 24px; height: 24px; min-width: 24px;"
                                            class="rounded-full object-cover"
                                        />
                                    @else
                                        <span
                                            class="rounded-full flex items-center justify-center text-[10px] font-bold text-white"
                                            style="width: 24px; height: 24px; min-width: 24px; background-color: {{ $meta['color'] ?? '#a1a1aa' }}"
                                        >{{ $meta['initials'] ?? mb_strtoupper(mb_substr($resource, 0, 1)) }}</span>
                                    @endif
                                    <span class="text-xs font-medium text-zinc-500 truncate">
                                        {{ $resource }}
                                    </span>
                                </div>
                            @else
                                <span class="text-xs font-medium text-zinc-500 text-center truncate">
                                    {{ $resource }}
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endif
