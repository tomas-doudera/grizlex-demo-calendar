<?php

return [
    'title' => 'Book a Reservation',

    'nav' => [
        'book' => 'Book Now',
    ],

    'steps' => [
        'service' => 'Service',
        'datetime' => 'Date & Time',
        'info' => 'Your Info',
        'review' => 'Review',
    ],

    'headings' => [
        'select_service' => 'Select a Service',
        'select_datetime' => 'Select Date & Time',
        'guest_info' => 'Your Information',
        'review' => 'Review Your Booking',
    ],

    'fields' => [
        'company' => 'Company',
        'place' => 'Location',
        'date' => 'Date',
        'time_slot' => 'Available Time Slots',
        'name' => 'Full Name',
        'email' => 'Email Address',
        'phone' => 'Phone Number',
        'notes' => 'Notes',
    ],

    'placeholders' => [
        'select_company' => 'Select a company...',
        'select_place' => 'Select a location...',
    ],

    'labels' => [
        'minutes' => 'min',
        'currency' => 'CZK',
        'payment_required' => 'Online Payment',
        'capacity' => 'Capacity',
    ],

    'review' => [
        'service' => 'Service',
        'place' => 'Location',
        'date' => 'Date',
        'time' => 'Time',
        'name' => 'Name',
        'email' => 'Email',
        'total' => 'Total',
    ],

    'buttons' => [
        'next' => 'Next',
        'back' => 'Back',
        'confirm' => 'Confirm Booking',
        'pay_with_stripe' => 'Pay with Stripe',
        'processing' => 'Processing...',
        'book_another' => 'Book Another',
        'go_home' => 'Go Home',
        'try_again' => 'Try Again',
    ],

    'messages' => [
        'choose_service' => 'Choose from the available services below.',
        'no_services' => 'No services available at this time.',
        'no_slots' => 'No available time slots for this date.',
        'stripe_redirect' => 'You will be redirected to Stripe to complete your payment securely.',
    ],

    'errors' => [
        'select_service' => 'Please select a service to continue.',
        'select_slot' => 'Please select a location, date, and time slot.',
        'slot_unavailable' => 'This time slot is no longer available. Please select another.',
    ],

    'success' => [
        'title' => 'Booking Confirmed',
        'heading' => 'Booking Confirmed!',
        'message' => 'Your reservation has been successfully created.',
        'payment_processing' => 'Your payment is being processed. You will receive a confirmation email shortly.',
    ],

    'cancel' => [
        'title' => 'Payment Cancelled',
        'heading' => 'Payment Cancelled',
        'message' => 'Your payment was cancelled. No charges have been made.',
    ],
];
