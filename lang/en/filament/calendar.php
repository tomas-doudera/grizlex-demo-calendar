<?php

return [
    'title_all_places' => 'All places',

    'filters' => [
        'date' => 'Date',
        'company' => 'Company',
        'place' => 'Place',
        'places' => 'Places',
        'venues' => 'Venues',
    ],
    'form' => [
        'booking_details' => 'Booking Details',
        'company' => 'Company',
        'venue' => 'Venue',
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
        'customers' => 'Customers',
        'customer_section' => 'Customer Information',
        'customer_name' => 'Customer Name',
        'customer_email' => 'Customer Email',
        'customer_phone' => 'Customer Phone',
        'notes' => 'Notes',
        'summary' => 'Reservation Summary',
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
