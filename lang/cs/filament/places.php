<?php

return [
    'label' => 'Místo',
    'plural_label' => 'Místa',

    'sections' => [
        'details' => 'Detaily místa',
        'booking_settings' => 'Nastavení rezervací',
    ],

    'fields' => [
        'title' => 'Název místa',
        'short_title' => 'Zkratka',
        'company' => 'Firma',
        'type' => 'Typ',
        'capacity' => 'Kapacita',
        'color' => 'Barva',
        'image_url' => 'URL obrázku',
        'description' => 'Popis',
        'is_active' => 'Aktivní',
        'min_booking_minutes' => 'Min. rezervace',
        'max_booking_minutes' => 'Max. rezervace',
        'booking_interval_minutes' => 'Interval rezervací',
        'advance_booking_days' => 'Rezervace předem',
        'cancellation_hours' => 'Lhůta pro zrušení',
    ],

    'columns' => [
        'title' => 'Místo',
        'company' => 'Firma',
        'active' => 'Aktivní',
        'reservations' => 'Rezervace',
    ],

    'filters' => [
        'company' => 'Firma',
        'type' => 'Typ',
        'is_active' => 'Aktivní',
    ],

    'types' => [
        'room' => 'Místnost',
        'court' => 'Kurt',
        'zone' => 'Zóna',
        'studio' => 'Studio',
        'field' => 'Hřiště',
        'pool' => 'Bazén',
    ],

    'suffixes' => [
        'days' => 'dní',
        'hours' => 'hodin',
    ],
];
