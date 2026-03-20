<?php

return [
    'label' => 'Personál',
    'plural_label' => 'Personál',

    'sections' => [
        'details' => 'Detaily personálu',
    ],

    'fields' => [
        'avatar_url' => 'Avatar',
        'first_name' => 'Jméno',
        'last_name' => 'Příjmení',
        'email' => 'E-mail',
        'phone' => 'Telefon',
        'company' => 'Firma',
        'role' => 'Role',
        'color' => 'Barva',
        'is_active' => 'Aktivní',
        'bio' => 'Bio',
    ],

    'columns' => [
        'name' => 'Jméno',
        'company' => 'Firma',
        'active' => 'Aktivní',
        'bookings' => 'Rezervace',
    ],

    'filters' => [
        'role' => 'Role',
        'company' => 'Firma',
        'is_active' => 'Aktivní',
    ],
];
