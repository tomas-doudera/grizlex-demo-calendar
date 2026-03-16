<?php

return [
    'reservation_status' => [
        'pending' => 'Čekající',
        'confirmed' => 'Potvrzeno',
        'checked_in' => 'Přihlášen',
        'completed' => 'Dokončeno',
        'cancelled' => 'Zrušeno',
        'no_show' => 'Nedostavil se',
    ],
    'staff_role' => [
        'manager' => 'Manažer',
        'instructor' => 'Instruktor',
        'receptionist' => 'Recepční',
        'trainer' => 'Trenér',
        'maintenance' => 'Údržba',
    ],
    'payment_method' => [
        'cash' => 'Hotovost',
        'credit_card' => 'Kreditní karta',
        'bank_transfer' => 'Bankovní převod',
        'online' => 'Online',
        'stripe' => 'Stripe',
    ],
    'reservation_type' => [
        'individual' => 'Individuální',
        'class' => 'Skupinová',
        'place' => 'Prostorová',
    ],
    'payment_status' => [
        'pending' => 'Čekající',
        'paid' => 'Zaplaceno',
        'partially_paid' => 'Částečně zaplaceno',
        'refunded' => 'Vráceno',
        'failed' => 'Neúspěšné',
    ],
    'day_of_week' => [
        'monday' => 'Pondělí',
        'tuesday' => 'Úterý',
        'wednesday' => 'Středa',
        'thursday' => 'Čtvrtek',
        'friday' => 'Pátek',
        'saturday' => 'Sobota',
        'sunday' => 'Neděle',
    ],
];
