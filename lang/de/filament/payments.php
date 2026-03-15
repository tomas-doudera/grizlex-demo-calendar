<?php

return [
    'label' => 'Zahlung',
    'plural_label' => 'Zahlungen',
    'sections' => [
        'details' => 'Zahlungsdetails',
        'status' => 'Status',
    ],
    'fields' => [
        'payment_number' => 'Zahlungsnummer',
        'amount' => 'Betrag',
        'reservation' => 'Reservierung',
        'customer' => 'Kunde',
        'transaction_id' => 'Transaktions-ID',
        'notes' => 'Notizen',
        'status' => 'Status',
        'method' => 'Methode',
        'paid_at' => 'Bezahlt am',
        'created_at' => 'Erstellt am',
    ],
    'columns' => [
        'customer' => 'Kunde',
        'not_paid' => 'Nicht bezahlt',
        'payment_number' => 'Zahlungsnummer',
        'amount' => 'Betrag',
        'status' => 'Status',
        'method' => 'Methode',
        'paid_at' => 'Bezahlt am',
        'created_at' => 'Erstellt am',
    ],
    'filters' => [
        'status' => 'Status',
        'method' => 'Methode',
    ],
];
