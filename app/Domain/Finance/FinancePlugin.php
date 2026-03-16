<?php

namespace App\Domain\Finance;

use App\Domain\Shared\DomainRegistry;
use Filament\Contracts\Plugin;
use Filament\Panel;

class FinancePlugin implements Plugin
{
    public static function make(): static
    {
        return new static;
    }

    public function getId(): string
    {
        return 'finance';
    }

    public function register(Panel $panel): void
    {
        if (! DomainRegistry::isEnabled('finance')) {
            return;
        }

        $panel
            ->discoverResources(
                in: __DIR__.'/Filament/Resources',
                for: 'App\Domain\Finance\Filament\Resources',
            )
            ->discoverWidgets(
                in: __DIR__.'/Filament/Widgets',
                for: 'App\Domain\Finance\Filament\Widgets',
            );
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
