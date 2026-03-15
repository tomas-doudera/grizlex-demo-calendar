<?php

return [
    'label' => 'Veranstaltungsort',
    'plural_label' => 'Veranstaltungsorte',
    'navigation_label' => 'Veranstaltungsorte',
    'sections' => [
        'details' => 'Veranstaltungsortdetails',
    ],
    'fields' => [
        'venue_name' => 'Name des Veranstaltungsortes',
        'short_name' => 'Kurzname',
        'company' => 'Unternehmen',
        'type' => 'Typ',
        'capacity' => 'Kapazität',
        'hourly_rate' => 'Stundensatz',
        'color' => 'Farbe',
        'is_active' => 'Aktiv',
        'description' => 'Beschreibung',
        'amenities' => 'Ausstattung',
    ],
    'columns' => [
        'venue' => 'Veranstaltungsort',
        'company' => 'Unternehmen',
        'active' => 'Aktiv',
        'bookings' => 'Buchungen',
        'type' => 'Typ',
        'capacity' => 'Kapazität',
        'hourly_rate' => 'Stundensatz',
    ],
    'filters' => [
        'company' => 'Unternehmen',
        'type' => 'Typ',
        'is_active' => 'Aktiv',
    ],
    'types' => [
        'court' => 'Platz',
        'room' => 'Raum',
        'pool' => 'Schwimmbad',
        'studio' => 'Studio',
        'field' => 'Spielfeld',
        'track' => 'Bahn',
    ],
];
