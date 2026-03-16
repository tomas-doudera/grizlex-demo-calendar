<?php

namespace App\Domain\Booking\Services;

use App\Domain\Booking\Enums\ReservationStatus;
use App\Domain\Booking\Events\ReservationCancelled;
use App\Domain\Booking\Events\ReservationCompleted;
use App\Domain\Booking\Events\ReservationConfirmed;
use App\Domain\Booking\Events\ReservationCreated;
use App\Domain\Booking\Events\ReservationNoShow;
use App\Domain\Booking\Models\Reservation;
use App\Domain\Shared\Enums\ReservationType;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class ReservationService
{
    public function __construct(
        private ConflictDetector $conflictDetector,
    ) {}

    /**
     * Create a new reservation.
     *
     * @param  array<string, mixed>  $data
     *
     * @throws \RuntimeException When a time conflict is detected.
     */
    public function create(array $data): Reservation
    {
        if (isset($data['from_time'], $data['to_time'])) {
            $from = $data['from_time'] instanceof CarbonInterface
                ? $data['from_time']
                : new Carbon($data['from_time']);
            $to = $data['to_time'] instanceof CarbonInterface
                ? $data['to_time']
                : new Carbon($data['to_time']);

            $constraints = array_filter([
                'place_id' => $data['place_id'] ?? null,
                'staff_id' => $data['staff_id'] ?? null,
            ]);

            if ($this->conflictDetector->hasConflict($from, $to, $constraints)) {
                throw new \RuntimeException('Time slot conflicts with an existing reservation.');
            }
        }

        $reservation = Reservation::create($data);

        event(new ReservationCreated(
            reservationId: $reservation->id,
            type: $data['type'] ?? ReservationType::Individual->value,
            customerId: $reservation->customer_id,
            companyId: $reservation->company_id,
            amount: (float) ($reservation->total_price ?? 0),
        ));

        return $reservation;
    }

    /**
     * Confirm a pending reservation.
     */
    public function confirm(Reservation $reservation): Reservation
    {
        $reservation->update(['status' => ReservationStatus::Confirmed]);

        event(new ReservationConfirmed(
            reservationId: $reservation->id,
            customerId: $reservation->customer_id,
            companyId: $reservation->company_id,
        ));

        return $reservation;
    }

    /**
     * Cancel a reservation.
     */
    public function cancel(Reservation $reservation, ?string $reason = null): Reservation
    {
        $reservation->update([
            'status' => ReservationStatus::Cancelled,
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
        ]);

        event(new ReservationCancelled(
            reservationId: $reservation->id,
            customerId: $reservation->customer_id,
            companyId: $reservation->company_id,
            amount: (float) ($reservation->total_price ?? 0),
            reason: $reason,
        ));

        return $reservation;
    }

    /**
     * Mark a reservation as completed.
     */
    public function complete(Reservation $reservation): Reservation
    {
        $reservation->update(['status' => ReservationStatus::Completed]);

        event(new ReservationCompleted(
            reservationId: $reservation->id,
            customerId: $reservation->customer_id,
            companyId: $reservation->company_id,
        ));

        return $reservation;
    }

    /**
     * Mark a reservation as no-show.
     */
    public function noShow(Reservation $reservation): Reservation
    {
        $reservation->update(['status' => ReservationStatus::NoShow]);

        event(new ReservationNoShow(
            reservationId: $reservation->id,
            customerId: $reservation->customer_id,
            companyId: $reservation->company_id,
        ));

        return $reservation;
    }
}
