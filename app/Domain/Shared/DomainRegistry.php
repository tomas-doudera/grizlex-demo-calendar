<?php

namespace App\Domain\Shared;

class DomainRegistry
{
    public static function isEnabled(string $domain): bool
    {
        return config("domains.{$domain}.enabled", false);
    }

    /** @return array<string> */
    public static function enabled(): array
    {
        return collect(config('domains', []))
            ->filter(fn (array $config): bool => $config['enabled'] ?? false)
            ->keys()
            ->all();
    }
}
