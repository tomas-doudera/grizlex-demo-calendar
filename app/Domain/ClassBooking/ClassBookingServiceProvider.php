<?php

namespace App\Domain\ClassBooking;

use Illuminate\Support\ServiceProvider;

class ClassBookingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/Config/class-booking.php', 'class-booking');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }
}
