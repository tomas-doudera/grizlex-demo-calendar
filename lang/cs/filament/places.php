<?php

return [
    'label' => 'Pobočka',
    'plural_label' => 'Pobočky',

    'tabs' => [
        'general' => 'Obecné',
        'address' => 'Adresa',
        'preferences' => 'Předvolby',
        'opening_hours' => 'Otevírací hodiny',
    ],

    'sections' => [
        'details' => 'Údaje o pobočce',
        'basic_info' => 'Základní údaje',
        'contact' => 'Kontakt',
        'address' => 'Adresa',
        'location' => 'Umístění',
        'preferences' => 'Předvolby',
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
        'sort_order' => 'Pořadí řazení',
    ],

    'columns' => [
        'title' => 'Název',
        'company' => 'Firma',
        'city' => 'Město',
        'venues' => 'Prostory',
        'active' => 'Aktivní',
        'created_at' => 'Vytvořeno',
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
