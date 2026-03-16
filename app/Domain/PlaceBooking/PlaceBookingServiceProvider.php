<?php

namespace App\Domain\PlaceBooking;

use Illuminate\Support\ServiceProvider;

class PlaceBookingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/Config/place-booking.php', 'place-booking');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }
}
