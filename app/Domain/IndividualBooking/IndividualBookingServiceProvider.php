<?php

namespace App\Domain\IndividualBooking;

use Illuminate\Support\ServiceProvider;

class IndividualBookingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/Config/individual-booking.php', 'individual-booking');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }
}
