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
    'order_status' => [
        'pending' => 'Čekající',
        'processing' => 'Zpracovává se',
        'shipped' => 'Odesláno',
        'delivered' => 'Doručeno',
        'cancelled' => 'Zrušeno',
        'refunded' => 'Vráceno',
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
    ],
    'payment_status' => [
        'pending' => 'Čekající',
        'paid' => 'Zaplaceno',
        'partially_paid' => 'Částečně zaplaceno',
        'refunded' => 'Vráceno',
        'failed' => 'Neúspěšné',
    ],
    'ticket_status' => [
        'open' => 'Otevřený',
        'in_progress' => 'Probíhá',
        'waiting_on_customer' => 'Čeká na zákazníka',
        'resolved' => 'Vyřešeno',
        'closed' => 'Uzavřeno',
    ],
    'ticket_priority' => [
        'low' => 'Nízká',
        'medium' => 'Střední',
        'high' => 'Vysoká',
        'critical' => 'Kritická',
    ],
    'project_status' => [
        'planning' => 'Plánování',
        'active' => 'Aktivní',
        'on_hold' => 'Pozastaveno',
        'completed' => 'Dokončeno',
        'cancelled' => 'Zrušeno',
    ],
    'product_status' => [
        'draft' => 'Koncept',
        'active' => 'Aktivní',
        'archived' => 'Archivováno',
        'discontinued' => 'Ukončeno',
    ],
];
