<?php

namespace App\Domain\Booking\Services;

use App\Domain\Booking\Enums\ReservationStatus;
use App\Domain\Booking\Models\Reservation;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ConflictDetector
{
    /**
     * Check if a time slot conflicts with existing reservations.
     *
     * @param  array{place_id?: int|null, staff_id?: int|null}  $constraints
     */
    public function hasConflict(
        CarbonInterface $from,
        CarbonInterface $to,
        array $constraints = [],
        ?int $excludeReservationId = null,
    ): bool {
        return $this->getConflictingQuery($from, $to, $constraints, $excludeReservationId)->exists();
    }

    /**
     * Get conflicting reservations for a given time slot.
     *
     * @param  array{place_id?: int|null, staff_id?: int|null}  $constraints
     */
    public function getConflicts(
        CarbonInterface $from,
        CarbonInterface $to,
        array $constraints = [],
        ?int $excludeReservationId = null,
    ): Collection {
        return $this->getConflictingQuery($from, $to, $constraints, $excludeReservationId)->get();
    }

    /**
     * @param  array{place_id?: int|null, staff_id?: int|null}  $constraints
     */
    private function getConflictingQuery(
        CarbonInterface $from,
        CarbonInterface $to,
        array $constraints,
        ?int $excludeReservationId,
    ): Builder {
        $query = Reservation::query()
            ->where(function (Builder $q) use ($from, $to): void {
                $q->where('from_time', '<', $to)
                    ->where('to_time', '>', $from);
            })
            ->whereNotIn('status', [ReservationStatus::Cancelled]);

        if ($excludeReservationId) {
            $query->where('id', '!=', $excludeReservationId);
        }

        if (! empty($constraints['place_id'])) {
            $query->where('place_id', $constraints['place_id']);
        }

        if (! empty($constraints['staff_id'])) {
            $query->where('staff_id', $constraints['staff_id']);
        }

        return $query;
    }
}
