<?php

namespace App\Domain\Booking;

use Illuminate\Support\ServiceProvider;

class BookingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/Config/booking.php', 'booking');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }
}
