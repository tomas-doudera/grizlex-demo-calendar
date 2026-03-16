<?php

namespace App\Domain\Booking\Events;

class ReservationCompleted
{
    public function __construct(
        public readonly int $reservationId,
        public readonly ?int $customerId,
        public readonly ?int $companyId,
    ) {}
}
