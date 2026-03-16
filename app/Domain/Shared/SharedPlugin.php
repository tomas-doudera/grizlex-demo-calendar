<?php

namespace App\Domain\Shared;

use Filament\Contracts\Plugin;
use Filament\Panel;

class SharedPlugin implements Plugin
{
    public static function make(): static
    {
        return new static;
    }

    public function getId(): string
    {
        return 'shared';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->discoverResources(
                in: __DIR__.'/Filament/Resources',
                for: 'App\Domain\Shared\Filament\Resources',
            );
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
