<?php

return [
    'label' => 'Rezervace',
    'plural_label' => 'Rezervace',

    'sections' => [
        'booking_details' => 'Detaily rezervace',
        'guest_information' => 'Informace o hostovi',
        'status' => 'Stav',
    ],

    'fields' => [
        'company' => 'Firma',
        'place' => 'Místo',
        'from_time' => 'Od',
        'to_time' => 'Do',
        'capacity' => 'Kapacita',
        'booked_count' => 'Obsazeno',
        'status' => 'Stav',
        'user' => 'Uživatel',
        'guest_name' => 'Jméno hosta',
        'guest_email' => 'E-mail hosta',
        'guest_phone' => 'Telefon hosta',
        'notes' => 'Poznámky',
        'confirmed_at' => 'Potvrzeno',
        'cancelled_at' => 'Zrušeno',
        'cancellation_reason' => 'Důvod zrušení',
    ],

    'columns' => [
        'date_time' => 'Datum a čas',
        'place' => 'Místo',
        'guest' => 'Host',
        'booked' => 'Obsazeno',
        'company' => 'Firma',
        'created_at' => 'Vytvořeno',
    ],

    'filters' => [
        'status' => 'Stav',
        'company' => 'Firma',
        'place' => 'Místo',
    ],
];
