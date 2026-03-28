<?php

return [
    'label' => 'Reservation',
    'plural_label' => 'Reservations',

    'sections' => [
        'booking_details' => 'Booking Details',
        'location_and_staff' => 'Location & staff',
        'time' => 'Time',
        'capacity' => 'Capacity',
        'guest_information' => 'Guest Information',
        'customer_information' => 'Customer Information',
        'status' => 'Status',
    ],

    'fields' => [
        'company' => 'Company',
        'venue' => 'Venue',
        'staff' => 'Trainer',
        'service' => 'Service',
        'from_time' => 'From',
        'to_time' => 'To',
        'capacity' => 'Capacity',
        'booked_count' => 'Booked',
        'status' => 'Status',
        'user' => 'User',
        'customers' => 'Customers',
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
        'venue' => 'Venue',
        'guest' => 'Guest',
        'booked' => 'Booked',
        'company' => 'Company',
        'created_at' => 'Created',
    ],

    'filters' => [
        'status' => 'Status',
        'company' => 'Company',
        'venue' => 'Venue',
    ],
];
