<?php

namespace App\Domain\ClassBooking;

use App\Domain\Shared\DomainRegistry;
use Filament\Contracts\Plugin;
use Filament\Panel;

class ClassBookingPlugin implements Plugin
{
    public static function make(): static
    {
        return new static;
    }

    public function getId(): string
    {
        return 'class-booking';
    }

    public function register(Panel $panel): void
    {
        if (! DomainRegistry::isEnabled('class-booking')) {
            return;
        }

        $panel
            ->discoverResources(
                in: __DIR__.'/Filament/Resources',
                for: 'App\Domain\ClassBooking\Filament\Resources',
            );
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
