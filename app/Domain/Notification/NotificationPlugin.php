<?php

namespace App\Domain\Notification;

use App\Domain\Shared\DomainRegistry;
use Filament\Contracts\Plugin;
use Filament\Panel;

class NotificationPlugin implements Plugin
{
    public static function make(): static
    {
        return new static;
    }

    public function getId(): string
    {
        return 'notification';
    }

    public function register(Panel $panel): void
    {
        if (! DomainRegistry::isEnabled('notification')) {
            return;
        }

        $panel
            ->discoverResources(
                in: __DIR__.'/Filament/Resources',
                for: 'App\Domain\Notification\Filament\Resources',
            );
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
