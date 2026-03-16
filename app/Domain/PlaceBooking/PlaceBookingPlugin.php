<?php

namespace App\Domain\PlaceBooking;

use App\Domain\Shared\DomainRegistry;
use Filament\Contracts\Plugin;
use Filament\Panel;

class PlaceBookingPlugin implements Plugin
{
    public static function make(): static
    {
        return new static;
    }

    public function getId(): string
    {
        return 'place-booking';
    }

    public function register(Panel $panel): void
    {
        if (! DomainRegistry::isEnabled('place-booking')) {
            return;
        }

        $panel
            ->discoverResources(
                in: __DIR__.'/Filament/Resources',
                for: 'App\Domain\PlaceBooking\Filament\Resources',
            )
            ->discoverPages(
                in: __DIR__.'/Filament/Pages',
                for: 'App\Domain\PlaceBooking\Filament\Pages',
            );
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
