<?php

return [
    'label' => 'Staff',
    'plural_label' => 'Staff',

    'sections' => [
        'details' => 'Staff Details',
    ],

    'fields' => [
        'avatar_url' => 'Avatar',
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'company' => 'Company',
        'role' => 'Role',
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
];
