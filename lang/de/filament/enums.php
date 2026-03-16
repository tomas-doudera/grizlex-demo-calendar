<?php

return [
    'reservation_status' => [
        'pending' => 'Ausstehend',
        'confirmed' => 'Bestätigt',
        'checked_in' => 'Eingecheckt',
        'completed' => 'Abgeschlossen',
        'cancelled' => 'Storniert',
        'no_show' => 'Nicht erschienen',
    ],
    'staff_role' => [
        'manager' => 'Manager',
        'instructor' => 'Kursleiter',
        'receptionist' => 'Rezeptionist',
        'trainer' => 'Trainer',
        'maintenance' => 'Wartung',
    ],
    'payment_method' => [
        'cash' => 'Bargeld',
        'credit_card' => 'Kreditkarte',
        'bank_transfer' => 'Banküberweisung',
        'online' => 'Online',
        'stripe' => 'Stripe',
    ],
    'reservation_type' => [
        'individual' => 'Einzeln',
        'class' => 'Kurs',
        'place' => 'Raum',
    ],
    'payment_status' => [
        'pending' => 'Ausstehend',
        'paid' => 'Bezahlt',
        'partially_paid' => 'Teilweise bezahlt',
        'refunded' => 'Erstattet',
        'failed' => 'Fehlgeschlagen',
    ],
    'day_of_week' => [
        'monday' => 'Montag',
        'tuesday' => 'Dienstag',
        'wednesday' => 'Mittwoch',
        'thursday' => 'Donnerstag',
        'friday' => 'Freitag',
        'saturday' => 'Samstag',
        'sunday' => 'Sonntag',
    ],
];
