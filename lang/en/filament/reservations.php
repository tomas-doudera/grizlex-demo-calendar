<?php

return [
    'label' => 'Reservation',
    'plural_label' => 'Reservations',

    'sections' => [
        'booking_details' => 'Booking Details',
        'guest_information' => 'Guest Information',
        'status' => 'Status',
    ],

    'fields' => [
        'company' => 'Company',
        'place' => 'Place',
        'from_time' => 'From',
        'to_time' => 'To',
        'capacity' => 'Capacity',
        'booked_count' => 'Booked',
        'status' => 'Status',
        'user' => 'User',
        'guest_name' => 'Guest Name',
        'guest_email' => 'Guest Email',
        'guest_phone' => 'Guest Phone',
        'notes' => 'Notes',
        'confirmed_at' => 'Confirmed At',
        'cancelled_at' => 'Cancelled At',
        'cancellation_reason' => 'Cancellation Reason',
    ],

    'columns' => [
        'date_time' => 'Date & Time',
        'place' => 'Place',
        'guest' => 'Guest',
        'booked' => 'Booked',
        'company' => 'Company',
        'created_at' => 'Created',
    ],

    'filters' => [
        'status' => 'Status',
        'company' => 'Company',
        'place' => 'Place',
    ],
];
