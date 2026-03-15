<?php

return [
    'label' => 'Platba',
    'plural_label' => 'Platby',
    'sections' => [
        'details' => 'Detaily platby',
        'status' => 'Stav',
    ],
    'fields' => [
        'payment_number' => 'Číslo platby',
        'amount' => 'Suma',
        'reservation' => 'Rezervácia',
        'customer' => 'Zákazník',
        'transaction_id' => 'ID transakcie',
        'notes' => 'Poznámky',
        'status' => 'Stav',
        'method' => 'Spôsob',
        'paid_at' => 'Zaplatené dňa',
        'created_at' => 'Vytvorené',
    ],
    'columns' => [
        'customer' => 'Zákazník',
        'not_paid' => 'Nezaplatené',
        'payment_number' => 'Číslo platby',
        'amount' => 'Suma',
        'status' => 'Stav',
        'method' => 'Spôsob',
        'paid_at' => 'Zaplatené dňa',
        'created_at' => 'Vytvorené',
    ],
    'filters' => [
        'status' => 'Stav',
        'method' => 'Spôsob',
    ],
];
