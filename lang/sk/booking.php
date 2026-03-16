<?php

return [
    'title' => 'Rezervácia',

    'nav' => [
        'book' => 'Rezervovať',
    ],

    'steps' => [
        'service' => 'Služba',
        'datetime' => 'Dátum a čas',
        'info' => 'Vaše údaje',
        'review' => 'Súhrn',
    ],

    'headings' => [
        'select_service' => 'Vyberte službu',
        'select_datetime' => 'Vyberte dátum a čas',
        'guest_info' => 'Vaše informácie',
        'review' => 'Skontrolujte rezerváciu',
    ],

    'fields' => [
        'company' => 'Spoločnosť',
        'place' => 'Miesto',
        'date' => 'Dátum',
        'time_slot' => 'Dostupné časy',
        'name' => 'Celé meno',
        'email' => 'E-mailová adresa',
        'phone' => 'Telefónne číslo',
        'notes' => 'Poznámky',
    ],

    'placeholders' => [
        'select_company' => 'Vyberte spoločnosť...',
        'select_place' => 'Vyberte miesto...',
    ],

    'labels' => [
        'minutes' => 'min',
        'currency' => 'Kč',
        'payment_required' => 'Online platba',
        'capacity' => 'Kapacita',
    ],

    'review' => [
        'service' => 'Služba',
        'place' => 'Miesto',
        'date' => 'Dátum',
        'time' => 'Čas',
        'name' => 'Meno',
        'email' => 'E-mail',
        'total' => 'Celkom',
    ],

    'buttons' => [
        'next' => 'Ďalej',
        'back' => 'Späť',
        'confirm' => 'Potvrdiť rezerváciu',
        'pay_with_stripe' => 'Zaplatiť cez Stripe',
        'processing' => 'Spracúvam...',
        'book_another' => 'Ďalšia rezervácia',
        'go_home' => 'Domov',
        'try_again' => 'Skúsiť znova',
    ],

    'messages' => [
        'choose_service' => 'Vyberte si z dostupných služieb nižšie.',
        'no_services' => 'V tejto chvíli nie sú k dispozícii žiadne služby.',
        'no_slots' => 'Pre tento deň nie sú dostupné žiadne časy.',
        'stripe_redirect' => 'Budete presmerovaní na Stripe pre bezpečné dokončenie platby.',
    ],

    'errors' => [
        'select_service' => 'Prosím vyberte službu pre pokračovanie.',
        'select_slot' => 'Prosím vyberte miesto, dátum a časový slot.',
        'slot_unavailable' => 'Tento čas už nie je dostupný. Prosím vyberte iný.',
    ],

    'success' => [
        'title' => 'Rezervácia potvrdená',
        'heading' => 'Rezervácia potvrdená!',
        'message' => 'Vaša rezervácia bola úspešne vytvorená.',
        'payment_processing' => 'Vaša platba sa spracúva. Čoskoro dostanete potvrdzovací e-mail.',
    ],

    'cancel' => [
        'title' => 'Platba zrušená',
        'heading' => 'Platba zrušená',
        'message' => 'Vaša platba bola zrušená. Nebola vykonaná žiadna úhrada.',
    ],
];
