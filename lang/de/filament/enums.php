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
    'order_status' => [
        'pending' => 'Ausstehend',
        'processing' => 'In Bearbeitung',
        'shipped' => 'Versendet',
        'delivered' => 'Zugestellt',
        'cancelled' => 'Storniert',
        'refunded' => 'Erstattet',
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
    ],
    'payment_status' => [
        'pending' => 'Ausstehend',
        'paid' => 'Bezahlt',
        'partially_paid' => 'Teilweise bezahlt',
        'refunded' => 'Erstattet',
        'failed' => 'Fehlgeschlagen',
    ],
    'ticket_status' => [
        'open' => 'Offen',
        'in_progress' => 'In Bearbeitung',
        'waiting_on_customer' => 'Warten auf Kunde',
        'resolved' => 'Gelöst',
        'closed' => 'Geschlossen',
    ],
    'ticket_priority' => [
        'low' => 'Niedrig',
        'medium' => 'Mittel',
        'high' => 'Hoch',
        'critical' => 'Kritisch',
    ],
    'project_status' => [
        'planning' => 'Planung',
        'active' => 'Aktiv',
        'on_hold' => 'Pausiert',
        'completed' => 'Abgeschlossen',
        'cancelled' => 'Storniert',
    ],
    'product_status' => [
        'draft' => 'Entwurf',
        'active' => 'Aktiv',
        'archived' => 'Archiviert',
        'discontinued' => 'Eingestellt',
    ],
];
