<?php

return [
    'title' => 'Rezervace',

    'nav' => [
        'book' => 'Rezervovat',
    ],

    'steps' => [
        'service' => 'Služba',
        'datetime' => 'Datum a čas',
        'info' => 'Vaše údaje',
        'review' => 'Souhrn',
    ],

    'headings' => [
        'select_service' => 'Vyberte službu',
        'select_datetime' => 'Vyberte datum a čas',
        'guest_info' => 'Vaše informace',
        'review' => 'Zkontrolujte rezervaci',
    ],

    'fields' => [
        'company' => 'Společnost',
        'place' => 'Místo',
        'date' => 'Datum',
        'time_slot' => 'Dostupné časy',
        'name' => 'Celé jméno',
        'email' => 'E-mailová adresa',
        'phone' => 'Telefonní číslo',
        'notes' => 'Poznámky',
    ],

    'placeholders' => [
        'select_company' => 'Vyberte společnost...',
        'select_place' => 'Vyberte místo...',
    ],

    'labels' => [
        'minutes' => 'min',
        'currency' => 'Kč',
        'payment_required' => 'Online platba',
        'capacity' => 'Kapacita',
    ],

    'review' => [
        'service' => 'Služba',
        'place' => 'Místo',
        'date' => 'Datum',
        'time' => 'Čas',
        'name' => 'Jméno',
        'email' => 'E-mail',
        'total' => 'Celkem',
    ],

    'buttons' => [
        'next' => 'Další',
        'back' => 'Zpět',
        'confirm' => 'Potvrdit rezervaci',
        'pay_with_stripe' => 'Zaplatit přes Stripe',
        'processing' => 'Zpracovávám...',
        'book_another' => 'Další rezervace',
        'go_home' => 'Domů',
        'try_again' => 'Zkusit znovu',
    ],

    'messages' => [
        'choose_service' => 'Vyberte si z dostupných služeb níže.',
        'no_services' => 'V tuto chvíli nejsou k dispozici žádné služby.',
        'no_slots' => 'Pro tento den nejsou dostupné žádné časy.',
        'stripe_redirect' => 'Budete přesměrováni na Stripe pro bezpečné dokončení platby.',
    ],

    'errors' => [
        'select_service' => 'Prosím vyberte službu pro pokračování.',
        'select_slot' => 'Prosím vyberte místo, datum a časový slot.',
        'slot_unavailable' => 'Tento čas již není dostupný. Prosím vyberte jiný.',
    ],

    'success' => [
        'title' => 'Rezervace potvrzena',
        'heading' => 'Rezervace potvrzena!',
        'message' => 'Vaše rezervace byla úspěšně vytvořena.',
        'payment_processing' => 'Vaše platba se zpracovává. Brzy obdržíte potvrzovací e-mail.',
    ],

    'cancel' => [
        'title' => 'Platba zrušena',
        'heading' => 'Platba zrušena',
        'message' => 'Vaše platba byla zrušena. Nebyla provedena žádná úhrada.',
    ],
];
