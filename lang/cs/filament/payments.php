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
        'amount' => 'Částka',
        'reservation' => 'Rezervace',
        'customer' => 'Zákazník',
        'transaction_id' => 'ID transakce',
        'notes' => 'Poznámky',
        'status' => 'Stav',
        'method' => 'Způsob platby',
        'paid_at' => 'Zaplaceno dne',
        'created_at' => 'Vytvořeno',
    ],
    'columns' => [
        'customer' => 'Zákazník',
        'not_paid' => 'Nezaplaceno',
        'payment_number' => 'Číslo platby',
        'amount' => 'Částka',
        'status' => 'Stav',
        'method' => 'Způsob platby',
        'paid_at' => 'Zaplaceno dne',
        'created_at' => 'Vytvořeno',
    ],
    'filters' => [
        'status' => 'Stav',
        'method' => 'Způsob platby',
    ],
];
