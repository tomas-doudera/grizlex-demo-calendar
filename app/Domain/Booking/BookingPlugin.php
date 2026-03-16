<?php

namespace App\Domain\Booking;

use App\Domain\Shared\DomainRegistry;
use Filament\Contracts\Plugin;
use Filament\Panel;

class BookingPlugin implements Plugin
{
    public static function make(): static
    {
        return new static;
    }

    public function getId(): string
    {
        return 'booking';
    }

    public function register(Panel $panel): void
    {
        if (! DomainRegistry::isEnabled('booking')) {
            return;
        }

        $panel
            ->discoverResources(
                in: __DIR__.'/Filament/Resources',
                for: 'App\Domain\Booking\Filament\Resources',
            )
            ->discoverPages(
                in: __DIR__.'/Filament/Pages',
                for: 'App\Domain\Booking\Filament\Pages',
            )
            ->discoverWidgets(
                in: __DIR__.'/Filament/Widgets',
                for: 'App\Domain\Booking\Filament\Widgets',
            );
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
