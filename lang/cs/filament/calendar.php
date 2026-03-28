<?php

return [
    'title_all_places' => 'Všechna místa',

    'filters' => [
        'date' => 'Datum',
        'company' => 'Společnost',
        'place' => 'Místo',
        'places' => 'Místa',
        'venues' => 'Prostory',
    ],
    'form' => [
        'booking_details' => 'Detaily rezervace',
        'company' => 'Společnost',
        'venue' => 'Prostor',
        'time' => 'Čas',
        'start' => 'Začátek',
        'end' => 'Konec',
        'capacity_section' => 'Kapacita a stav',
        'capacity' => 'Kapacita',
        'booked_count' => 'Obsazeno',
        'status' => 'Stav',
        'guest_section' => 'Informace o hostovi',
        'guest_name' => 'Jméno hosta',
        'guest_email' => 'E-mail hosta',
        'guest_phone' => 'Telefon hosta',
        'customers' => 'Zákazníci',
        'customer_section' => 'Informace o zákazníkovi',
        'customer_name' => 'Jméno zákazníka',
        'customer_email' => 'E-mail zákazníka',
        'customer_phone' => 'Telefon zákazníka',
        'notes' => 'Poznámky',
        'summary' => 'Přehled rezervace',
    ],
    'actions' => [
        'delete' => 'Smazat',
    ],
    'validation' => [
        'venue_closed' => 'Provozovna je v :day zavřená.',
        'time_range' => 'Čas musí být mezi :min:00 a :max:00.',
        'end_after_start' => 'Čas konce musí být pozdější než začátek.',
        'time_occupied' => 'Časový slot je již obsazený.',
    ],
];
