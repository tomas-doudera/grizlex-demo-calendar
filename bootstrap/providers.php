<?php

use App\Domain\Booking\BookingServiceProvider;
use App\Domain\ClassBooking\ClassBookingServiceProvider;
use App\Domain\Finance\FinanceServiceProvider;
use App\Domain\IndividualBooking\IndividualBookingServiceProvider;
use App\Domain\Notification\NotificationServiceProvider;
use App\Domain\PlaceBooking\PlaceBookingServiceProvider;
use App\Domain\Shared\SharedServiceProvider;
use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\FortifyServiceProvider;
use TomasDoudera\CalMe\CalMeServiceProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    FortifyServiceProvider::class,
    CalMeServiceProvider::class,

    // Domain providers
    SharedServiceProvider::class,
    BookingServiceProvider::class,
    IndividualBookingServiceProvider::class,
    ClassBookingServiceProvider::class,
    PlaceBookingServiceProvider::class,
    FinanceServiceProvider::class,
    NotificationServiceProvider::class,
];
