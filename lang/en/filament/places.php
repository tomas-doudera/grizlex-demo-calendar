<?php

return [
    'label' => 'Place',
    'plural_label' => 'Places',

    'tabs' => [
        'general' => 'General',
        'address' => 'Address',
        'preferences' => 'Preferences',
        'opening_hours' => 'Opening hours',
    ],

    'sections' => [
        'details' => 'Place details',
        'basic_info' => 'Basic Info',
        'contact' => 'Contact',
        'address' => 'Address',
        'location' => 'Location',
        'preferences' => 'Preferences',
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
        'sort_order' => 'Sort order',
    ],

    'columns' => [
        'title' => 'Name',
        'company' => 'Company',
        'city' => 'City',
        'venues' => 'Venues',
        'active' => 'Active',
        'created_at' => 'Created',
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
