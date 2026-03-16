<?php

namespace App\Domain\Shared\Contracts;

use App\Domain\Shared\Enums\ReservationType;

interface Reservable
{
    public function getReservationType(): ReservationType;

    public function getDurationMinutes(): int;

    public function getPriceAmount(): float;
}
