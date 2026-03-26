<?php

return [
    'title' => 'Calendar settings',

    'sections' => [
        'display' => 'Calendar display',
    ],

    'fields' => [
        'step_width' => 'Step width',
        'row_height' => 'Row height',
    ],

    'helpers' => [
        'step_width' => 'Width of one 15-minute step in the calendar (px). Higher value = wider cells.',
        'row_height' => 'Height of one resource row in the calendar (px).',
    ],

    'actions' => [
        'save' => 'Save',
    ],

    'notifications' => [
        'saved' => 'Calendar settings saved.',
    ],
];
