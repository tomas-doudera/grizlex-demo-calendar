<?php

return [
    'label' => 'Place',
    'plural_label' => 'Places',

    'sections' => [
        'details' => 'Place Details',
        'booking_settings' => 'Booking Settings',
    ],

    'fields' => [
        'title' => 'Place Name',
        'short_title' => 'Short Name',
        'company' => 'Company',
        'type' => 'Type',
        'capacity' => 'Capacity',
        'color' => 'Color',
        'image_url' => 'Image URL',
        'description' => 'Description',
        'is_active' => 'Active',
        'min_booking_minutes' => 'Min. Booking',
        'max_booking_minutes' => 'Max. Booking',
        'booking_interval_minutes' => 'Booking Interval',
        'advance_booking_days' => 'Advance Booking',
        'cancellation_hours' => 'Cancellation Deadline',
    ],

    'columns' => [
        'title' => 'Place',
        'company' => 'Company',
        'active' => 'Active',
        'reservations' => 'Reservations',
    ],

    'filters' => [
        'company' => 'Company',
        'type' => 'Type',
        'is_active' => 'Active',
    ],

    'types' => [
        'room' => 'Room',
        'court' => 'Court',
        'zone' => 'Zone',
        'studio' => 'Studio',
        'field' => 'Field',
        'pool' => 'Pool',
    ],

    'suffixes' => [
        'days' => 'days',
        'hours' => 'hours',
    ],
];
