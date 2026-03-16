<?php

namespace App\Domain\Booking\Events;

class ReservationCancelled
{
    public function __construct(
        public readonly int $reservationId,
        public readonly ?int $customerId,
        public readonly ?int $companyId,
        public readonly float $amount,
        public readonly ?string $reason,
    ) {}
}
