<?php

return [
    'label' => 'Place',
    'plural_label' => 'Places',

    'tabs' => [
        'general' => 'General',
        'opening_hours' => 'Opening hours',
    ],

    'sections' => [
        'details' => 'Place details',
        'contact' => 'Contact & address',
        'opening_hours' => 'Weekly schedule',
        'opening_hours_description' => 'Used by the calendar for allowed booking times. For closed days, set both handles to 0:00, or use the same opening and closing time.',
    ],

    'days' => [
        'monday' => 'Monday',
        'tuesday' => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday' => 'Thursday',
        'friday' => 'Friday',
        'saturday' => 'Saturday',
        'sunday' => 'Sunday',
    ],

    'fields' => [
        'title' => 'Place name',
        'company' => 'Company',
        'email' => 'Email',
        'phone' => 'Phone',
        'address' => 'Address',
        'city' => 'City',
        'postal_code' => 'Postal code',
        'country' => 'Country',
        'description' => 'Description',
        'is_active' => 'Active',
    ],

    'columns' => [
        'title' => 'Name',
        'company' => 'Company',
        'city' => 'City',
        'venues' => 'Venues',
        'active' => 'Active',
    ],

    'filters' => [
        'company' => 'Company',
        'is_active' => 'Active',
    ],

    'suffixes' => [
        'days' => 'days',
        'hours' => 'hours',
    ],
];
