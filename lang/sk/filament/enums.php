<?php

return [
    'reservation_status' => [
        'pending' => 'Čakajúca',
        'confirmed' => 'Potvrdená',
        'checked_in' => 'Prihlásená',
        'completed' => 'Dokončená',
        'cancelled' => 'Zrušená',
        'no_show' => 'Neprišiel',
    ],
    'staff_role' => [
        'manager' => 'Manažér',
        'instructor' => 'Inštruktor',
        'receptionist' => 'Recepčný',
        'trainer' => 'Tréner',
        'maintenance' => 'Údržbár',
    ],
    'payment_method' => [
        'cash' => 'Hotovosť',
        'credit_card' => 'Kreditná karta',
        'bank_transfer' => 'Bankový prevod',
        'online' => 'Online',
        'stripe' => 'Stripe',
    ],
    'reservation_type' => [
        'individual' => 'Individuálna',
        'class' => 'Skupinová',
        'place' => 'Priestorová',
    ],
    'payment_status' => [
        'pending' => 'Čakajúca',
        'paid' => 'Zaplatená',
        'partially_paid' => 'Čiastočne zaplatená',
        'refunded' => 'Vrátená',
        'failed' => 'Zlyhala',
    ],
    'day_of_week' => [
        'monday' => 'Pondelok',
        'tuesday' => 'Utorok',
        'wednesday' => 'Streda',
        'thursday' => 'Štvrtok',
        'friday' => 'Piatok',
        'saturday' => 'Sobota',
        'sunday' => 'Nedeľa',
    ],
];
