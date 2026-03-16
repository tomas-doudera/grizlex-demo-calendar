<?php

namespace App\Domain\Booking\Events;

class ReservationConfirmed
{
    public function __construct(
        public readonly int $reservationId,
        public readonly ?int $customerId,
        public readonly ?int $companyId,
    ) {}
}
