<?php

return [
    'label' => 'Člen personálu',
    'plural_label' => 'Personál',
    'sections' => [
        'details' => 'Detaily personálu',
    ],
    'fields' => [
        'avatar' => 'Avatar',
        'first_name' => 'Meno',
        'last_name' => 'Priezvisko',
        'email' => 'Email',
        'phone' => 'Telefón',
        'company' => 'Spoločnosť',
        'role' => 'Pozícia',
        'specialization' => 'Špecializácia',
        'color' => 'Farba',
        'is_active' => 'Aktívny',
        'bio' => 'Bio',
    ],
    'columns' => [
        'name' => 'Meno',
        'company' => 'Spoločnosť',
        'active' => 'Aktívny',
        'bookings' => 'Rezervácie',
    ],
    'filters' => [
        'role' => 'Pozícia',
        'company' => 'Spoločnosť',
        'is_active' => 'Aktívny',
    ],
    'breaks' => [
        'date' => 'Konkrétny dátum',
        'date_help' => 'Ponechajte prázdne pre opakujúcu sa týždennú prestávku.',
        'day_of_week' => 'Deň v týždni',
        'day_of_week_help' => 'Pre opakujúce sa týždenné prestávky. Ignorované, ak je nastavený dátum.',
        'recurring' => 'Opakujúca sa',
    ],
];
