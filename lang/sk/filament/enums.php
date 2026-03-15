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
    'order_status' => [
        'pending' => 'Čakajúca',
        'processing' => 'Spracováva sa',
        'shipped' => 'Odoslaná',
        'delivered' => 'Doručená',
        'cancelled' => 'Zrušená',
        'refunded' => 'Vrátená',
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
    ],
    'payment_status' => [
        'pending' => 'Čakajúca',
        'paid' => 'Zaplatená',
        'partially_paid' => 'Čiastočne zaplatená',
        'refunded' => 'Vrátená',
        'failed' => 'Zlyhala',
    ],
    'ticket_status' => [
        'open' => 'Otvorený',
        'in_progress' => 'Prebieha',
        'waiting_on_customer' => 'Čaká sa na zákazníka',
        'resolved' => 'Vyriešený',
        'closed' => 'Uzavretý',
    ],
    'ticket_priority' => [
        'low' => 'Nízka',
        'medium' => 'Stredná',
        'high' => 'Vysoká',
        'critical' => 'Kritická',
    ],
    'project_status' => [
        'planning' => 'Plánovanie',
        'active' => 'Aktívny',
        'on_hold' => 'Pozastavený',
        'completed' => 'Dokončený',
        'cancelled' => 'Zrušený',
    ],
    'product_status' => [
        'draft' => 'Koncept',
        'active' => 'Aktívny',
        'archived' => 'Archivovaný',
        'discontinued' => 'Ukončený',
    ],
];
