<?php

return [
    'label' => 'Staff Member',
    'plural_label' => 'Staff',
    'sections' => [
        'details' => 'Staff Details',
    ],
    'fields' => [
        'avatar' => 'Avatar',
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'company' => 'Company',
        'role' => 'Role',
        'specialization' => 'Specialization',
        'color' => 'Color',
        'is_active' => 'Active',
        'bio' => 'Bio',
    ],
    'columns' => [
        'name' => 'Name',
        'company' => 'Company',
        'active' => 'Active',
        'bookings' => 'Bookings',
    ],
    'filters' => [
        'role' => 'Role',
        'company' => 'Company',
        'is_active' => 'Active',
    ],
    'breaks' => [
        'date' => 'Specific Date',
        'date_help' => 'Leave empty for a recurring weekly break.',
        'day_of_week' => 'Day of Week',
        'day_of_week_help' => 'For recurring weekly breaks. Ignored if a specific date is set.',
        'recurring' => 'Recurring',
    ],
];
