<?php

return [
    'label' => 'Projekt',
    'plural_label' => 'Projekte',
    'sections' => [
        'details' => 'Projektdetails',
        'progress_budget' => 'Fortschritt & Budget',
    ],
    'fields' => [
        'name' => 'Name',
        'description' => 'Beschreibung',
        'customer' => 'Kunde',
        'status' => 'Status',
        'start_date' => 'Startdatum',
        'due_date' => 'Fälligkeitsdatum',
        'progress' => 'Fortschritt',
        'budget' => 'Budget',
        'spent' => 'Ausgegeben',
        'color' => 'Farbe',
    ],
    'columns' => [
        'customer' => 'Kunde',
        'no_customer' => 'Kein Kunde',
        'progress' => 'Fortschritt',
        'budget' => 'Budget',
        'spent' => 'Ausgegeben',
        'deadline' => 'Frist',
        'name' => 'Name',
        'status' => 'Status',
        'start_date' => 'Startdatum',
    ],
    'filters' => [
        'status' => 'Status',
        'customer' => 'Kunde',
    ],
];
