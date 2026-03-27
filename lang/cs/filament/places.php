<?php

return [
    'label' => 'Pobočka',
    'plural_label' => 'Pobočky',

    'tabs' => [
        'general' => 'Obecné',
        'opening_hours' => 'Otevírací hodiny',
    ],

    'sections' => [
        'details' => 'Údaje o pobočce',
        'contact' => 'Kontakt a adresa',
        'opening_hours' => 'Týdenní rozvrh',
        'opening_hours_description' => 'Kalendář podle toho povolí časy rezervací. Pro zavřené dny nastavte 0:00 nebo stejnou otevírací a zavírací dobu.',
    ],

    'opening_hours_placeholder' => 'např. 09:00-12:00, 13:00-17:00',

    'days' => [
        'monday' => 'Pondělí',
        'tuesday' => 'Úterý',
        'wednesday' => 'Středa',
        'thursday' => 'Čtvrtek',
        'friday' => 'Pátek',
        'saturday' => 'Sobota',
        'sunday' => 'Neděle',
    ],

    'fields' => [
        'title' => 'Název pobočky',
        'company' => 'Firma',
        'email' => 'E-mail',
        'phone' => 'Telefon',
        'address' => 'Adresa',
        'city' => 'Město',
        'postal_code' => 'PSČ',
        'country' => 'Země',
        'description' => 'Popis',
        'is_active' => 'Aktivní',
    ],

    'columns' => [
        'title' => 'Název',
        'company' => 'Firma',
        'city' => 'Město',
        'venues' => 'Prostory',
        'active' => 'Aktivní',
    ],

    'filters' => [
        'company' => 'Firma',
        'is_active' => 'Aktivní',
    ],

    'suffixes' => [
        'days' => 'dní',
        'hours' => 'hodin',
    ],
];
