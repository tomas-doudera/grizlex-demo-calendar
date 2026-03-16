<?php

namespace App\Domain\Shared\Contracts;

use Carbon\CarbonImmutable;

interface HasAvailability
{
    public function isAvailableAt(CarbonImmutable $dateTime, int $durationMinutes): bool;
}
