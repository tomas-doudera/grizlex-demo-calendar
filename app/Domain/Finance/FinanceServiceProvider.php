<?php

namespace App\Domain\Finance;

use App\Domain\Booking\Events\ReservationCreated;
use App\Domain\Finance\Listeners\CreatePendingPayment;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class FinanceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/Config/finance.php', 'finance');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        Event::listen(ReservationCreated::class, CreatePendingPayment::class);
    }
}
