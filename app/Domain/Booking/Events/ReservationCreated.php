<?php

namespace App\Domain\Booking\Events;

class ReservationCreated
{
    public function __construct(
        public readonly int $reservationId,
        public readonly string $type,
        public readonly ?int $customerId,
        public readonly ?int $companyId,
        public readonly float $amount,
    ) {}
}
