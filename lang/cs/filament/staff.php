<?php

return [
    'label' => 'Člen personálu',
    'plural_label' => 'Personál',
    'sections' => [
        'details' => 'Detaily personálu',
    ],
    'fields' => [
        'avatar' => 'Avatar',
        'first_name' => 'Jméno',
        'last_name' => 'Příjmení',
        'email' => 'E-mail',
        'phone' => 'Telefon',
        'company' => 'Společnost',
        'role' => 'Role',
        'specialization' => 'Specializace',
        'color' => 'Barva',
        'is_active' => 'Aktivní',
        'bio' => 'Bio',
    ],
    'columns' => [
        'name' => 'Jméno',
        'company' => 'Společnost',
        'active' => 'Aktivní',
        'bookings' => 'Rezervace',
    ],
    'filters' => [
        'role' => 'Role',
        'company' => 'Společnost',
        'is_active' => 'Aktivní',
    ],
    'breaks' => [
        'date' => 'Konkrétní datum',
        'date_help' => 'Ponechte prázdné pro opakující se týdenní přestávku.',
        'day_of_week' => 'Den v týdnu',
        'day_of_week_help' => 'Pro opakující se týdenní přestávky. Ignorováno, pokud je nastaveno datum.',
        'recurring' => 'Opakující se',
    ],
];
