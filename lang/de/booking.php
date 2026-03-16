<?php

return [
    'title' => 'Reservierung buchen',

    'nav' => [
        'book' => 'Jetzt buchen',
    ],

    'steps' => [
        'service' => 'Dienstleistung',
        'datetime' => 'Datum & Uhrzeit',
        'info' => 'Ihre Daten',
        'review' => 'Überprüfung',
    ],

    'headings' => [
        'select_service' => 'Dienstleistung wählen',
        'select_datetime' => 'Datum & Uhrzeit wählen',
        'guest_info' => 'Ihre Informationen',
        'review' => 'Buchung überprüfen',
    ],

    'fields' => [
        'company' => 'Unternehmen',
        'place' => 'Standort',
        'date' => 'Datum',
        'time_slot' => 'Verfügbare Zeiten',
        'name' => 'Vollständiger Name',
        'email' => 'E-Mail-Adresse',
        'phone' => 'Telefonnummer',
        'notes' => 'Anmerkungen',
    ],

    'placeholders' => [
        'select_company' => 'Unternehmen auswählen...',
        'select_place' => 'Standort auswählen...',
    ],

    'labels' => [
        'minutes' => 'Min.',
        'currency' => 'CZK',
        'payment_required' => 'Online-Zahlung',
        'capacity' => 'Kapazität',
    ],

    'review' => [
        'service' => 'Dienstleistung',
        'place' => 'Standort',
        'date' => 'Datum',
        'time' => 'Uhrzeit',
        'name' => 'Name',
        'email' => 'E-Mail',
        'total' => 'Gesamt',
    ],

    'buttons' => [
        'next' => 'Weiter',
        'back' => 'Zurück',
        'confirm' => 'Buchung bestätigen',
        'pay_with_stripe' => 'Mit Stripe bezahlen',
        'processing' => 'Wird verarbeitet...',
        'book_another' => 'Weitere Buchung',
        'go_home' => 'Startseite',
        'try_again' => 'Erneut versuchen',
    ],

    'messages' => [
        'choose_service' => 'Wählen Sie aus den verfügbaren Dienstleistungen.',
        'no_services' => 'Derzeit sind keine Dienstleistungen verfügbar.',
        'no_slots' => 'Für dieses Datum sind keine Zeiten verfügbar.',
        'stripe_redirect' => 'Sie werden zu Stripe weitergeleitet, um Ihre Zahlung sicher abzuschließen.',
    ],

    'errors' => [
        'select_service' => 'Bitte wählen Sie eine Dienstleistung aus.',
        'select_slot' => 'Bitte wählen Sie Standort, Datum und Zeitfenster.',
        'slot_unavailable' => 'Dieses Zeitfenster ist nicht mehr verfügbar. Bitte wählen Sie ein anderes.',
    ],

    'success' => [
        'title' => 'Buchung bestätigt',
        'heading' => 'Buchung bestätigt!',
        'message' => 'Ihre Reservierung wurde erfolgreich erstellt.',
        'payment_processing' => 'Ihre Zahlung wird verarbeitet. Sie erhalten in Kürze eine Bestätigungs-E-Mail.',
    ],

    'cancel' => [
        'title' => 'Zahlung abgebrochen',
        'heading' => 'Zahlung abgebrochen',
        'message' => 'Ihre Zahlung wurde abgebrochen. Es wurden keine Gebühren erhoben.',
    ],
];
