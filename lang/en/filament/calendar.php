<?php

return [
    'filters' => [
        'date' => 'Date',
        'company' => 'Company',
        'places' => 'Places',
    ],
    'form' => [
        'booking_details' => 'Booking Details',
        'company' => 'Company',
        'place' => 'Place',
        'time' => 'Time',
        'start' => 'Start',
        'end' => 'End',
        'capacity_section' => 'Capacity & Status',
        'capacity' => 'Capacity',
        'booked_count' => 'Booked',
        'status' => 'Status',
        'guest_section' => 'Guest Information',
        'guest_name' => 'Guest Name',
        'guest_email' => 'Guest Email',
        'guest_phone' => 'Guest Phone',
        'notes' => 'Notes',
    ],
    'actions' => [
        'delete' => 'Delete',
    ],
    'validation' => [
        'venue_closed' => 'The venue is closed on :day.',
        'time_range' => 'Time must be between :min:00 and :max:00.',
        'end_after_start' => 'The end time must be later than the start.',
        'time_occupied' => 'The time slot is already occupied.',
    ],
];
