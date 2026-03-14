<?php

// config for TomasDoudera/CalMe
return [
    'default_view' => 'week-vertical',
    'enabled_views' => [
        'week-vertical' => [
            'label' => 'cal-me::labels.views.week_vertical',
            'icon' => 'heroicon-o-calendar-days',
            'enabled' => true,
        ],
        'week-horizontal' => [
            'label' => 'cal-me::labels.views.week_horizontal',
            'icon' => 'heroicon-o-calendar-date-range',
            'enabled' => true,
        ],
        'day-horizontal' => [
            'label' => 'cal-me::labels.views.day_horizontal',
            'icon' => 'heroicon-o-calendar',
            'enabled' => true,
        ],
        'month' => [
            'label' => 'cal-me::labels.views.month',
            'icon' => 'heroicon-o-calendar-days',
            'enabled' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cell Dimensions
    |--------------------------------------------------------------------------
    |
    | Configure the dimensions (width and height in pixels) of calendar cells
    | for each view type. These values control the size of time slots.
    |
    */
    'cell_dimensions' => [
        'week-vertical' => [
            'cell_width' => 100,      // Width of each hour cell (px)
            'cell_height' => 50,      // Height of each resource row (px)
            'sidebar_width' => 140,   // Width of the day/resource sidebar (px)
        ],
        'week-horizontal' => [
            'cell_width' => 160,      // Width of each resource column (px)
            'cell_width_mobile' => 96, // Width on mobile screens (px)
            'cell_height' => 80,      // Height of each hour row (px)
            'sidebar_width' => 140,   // Width of the hours sidebar (px)
        ],
        'day-horizontal' => [
            'cell_width' => 160,      // Width of each resource column (px)
            'cell_width_mobile' => 96, // Width on mobile screens (px)
            'cell_height' => 80,      // Height of each hour row (px)
            'sidebar_width' => 140,   // Width of the hours sidebar (px)
        ],
        'month' => [
            'day_min_height' => 120,  // Minimum height of each day cell (px)
        ],
    ],
];
