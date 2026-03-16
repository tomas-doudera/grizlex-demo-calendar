<?php

namespace App\Domain\IndividualBooking;

use App\Domain\Shared\DomainRegistry;
use Filament\Contracts\Plugin;
use Filament\Panel;

class IndividualBookingPlugin implements Plugin
{
    public static function make(): static
    {
        return new static;
    }

    public function getId(): string
    {
        return 'individual-booking';
    }

    public function register(Panel $panel): void
    {
        if (! DomainRegistry::isEnabled('individual-booking')) {
            return;
        }

        $panel
            ->discoverResources(
                in: __DIR__.'/Filament/Resources',
                for: 'App\Domain\IndividualBooking\Filament\Resources',
            )
            ->discoverPages(
                in: __DIR__.'/Filament/Pages',
                for: 'App\Domain\IndividualBooking\Filament\Pages',
            );
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
