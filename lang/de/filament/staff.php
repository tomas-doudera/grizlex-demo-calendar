<?php

return [
    'label' => 'Mitarbeiter',
    'plural_label' => 'Mitarbeiter',
    'sections' => [
        'details' => 'Mitarbeiterdetails',
    ],
    'fields' => [
        'avatar' => 'Avatar',
        'first_name' => 'Vorname',
        'last_name' => 'Nachname',
        'email' => 'E-Mail',
        'phone' => 'Telefon',
        'company' => 'Unternehmen',
        'role' => 'Rolle',
        'specialization' => 'Spezialisierung',
        'color' => 'Farbe',
        'is_active' => 'Aktiv',
        'bio' => 'Biografie',
    ],
    'columns' => [
        'name' => 'Name',
        'company' => 'Unternehmen',
        'active' => 'Aktiv',
        'bookings' => 'Buchungen',
    ],
    'filters' => [
        'role' => 'Rolle',
        'company' => 'Unternehmen',
        'is_active' => 'Aktiv',
    ],
    'breaks' => [
        'date' => 'Bestimmtes Datum',
        'date_help' => 'Leer lassen für eine wöchentlich wiederkehrende Pause.',
        'day_of_week' => 'Wochentag',
        'day_of_week_help' => 'Für wöchentlich wiederkehrende Pausen. Wird ignoriert, wenn ein Datum gesetzt ist.',
        'recurring' => 'Wiederkehrend',
    ],
];
