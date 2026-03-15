<?php

return [
    'label' => 'Venue',
    'plural_label' => 'Venues',
    'navigation_label' => 'Venues',
    'sections' => [
        'details' => 'Venue Details',
    ],
    'fields' => [
        'venue_name' => 'Venue Name',
        'short_name' => 'Short Name',
        'company' => 'Company',
        'type' => 'Type',
        'capacity' => 'Capacity',
        'hourly_rate' => 'Hourly Rate',
        'color' => 'Color',
        'is_active' => 'Active',
        'description' => 'Description',
        'amenities' => 'Amenities',
    ],
    'columns' => [
        'venue' => 'Venue',
        'company' => 'Company',
        'active' => 'Active',
        'bookings' => 'Bookings',
        'type' => 'Type',
        'capacity' => 'Capacity',
        'hourly_rate' => 'Hourly Rate',
    ],
    'filters' => [
        'company' => 'Company',
        'type' => 'Type',
        'is_active' => 'Active',
    ],
    'types' => [
        'court' => 'Court',
        'room' => 'Room',
        'pool' => 'Pool',
        'studio' => 'Studio',
        'field' => 'Field',
        'track' => 'Track',
    ],
];
