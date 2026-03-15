<?php

return [
    'label' => 'Payment',
    'plural_label' => 'Payments',
    'sections' => [
        'details' => 'Payment Details',
        'status' => 'Status',
    ],
    'fields' => [
        'payment_number' => 'Payment Number',
        'amount' => 'Amount',
        'reservation' => 'Reservation',
        'customer' => 'Customer',
        'transaction_id' => 'Transaction ID',
        'notes' => 'Notes',
        'status' => 'Status',
        'method' => 'Method',
        'paid_at' => 'Paid At',
        'created_at' => 'Created At',
    ],
    'columns' => [
        'customer' => 'Customer',
        'not_paid' => 'Not paid',
        'payment_number' => 'Payment Number',
        'amount' => 'Amount',
        'status' => 'Status',
        'method' => 'Method',
        'paid_at' => 'Paid At',
        'created_at' => 'Created At',
    ],
    'filters' => [
        'status' => 'Status',
        'method' => 'Method',
    ],
];
