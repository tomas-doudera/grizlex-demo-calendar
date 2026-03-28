<?php

return [
    'label' => 'Rezervace',
    'plural_label' => 'Rezervace',

    'sections' => [
        'booking_details' => 'Detaily rezervace',
        'location_and_staff' => 'Místo a trenér',
        'time' => 'Čas',
        'capacity' => 'Kapacita',
        'guest_information' => 'Informace o hostovi',
        'customer_information' => 'Informace o zákazníkovi',
        'status' => 'Stav',
    ],

    'fields' => [
        'company' => 'Firma',
        'venue' => 'Prostor',
        'staff' => 'Trenér',
        'service' => 'Služba',
        'from_time' => 'Od',
        'to_time' => 'Do',
        'capacity' => 'Kapacita',
        'booked_count' => 'Obsazeno',
        'status' => 'Stav',
        'user' => 'Uživatel',
        'customers' => 'Zákazníci',
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
        'venue' => 'Prostor',
        'guest' => 'Host',
        'booked' => 'Obsazeno',
        'company' => 'Firma',
        'created_at' => 'Vytvořeno',
    ],

    'filters' => [
        'status' => 'Stav',
        'company' => 'Firma',
        'venue' => 'Prostor',
    ],
];
