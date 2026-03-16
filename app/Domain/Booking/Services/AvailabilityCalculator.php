<?php

namespace App\Domain\Booking\Services;

use Carbon\CarbonInterface;

class AvailabilityCalculator
{
    public function __construct(
        private ConflictDetector $conflictDetector,
    ) {}

    /**
     * Get available time slots for a given date and resource.
     *
     * @param  array{place_id?: int|null, staff_id?: int|null}  $constraints
     * @return array<int, array{from: CarbonInterface, to: CarbonInterface}>
     */
    public function getAvailableSlots(
        CarbonInterface $date,
        int $durationMinutes,
        int $intervalMinutes,
        array $constraints = [],
        ?CarbonInterface $dayStart = null,
        ?CarbonInterface $dayEnd = null,
    ): array {
        $start = $dayStart ?? $date->copy()->setTime(8, 0);
        $end = $dayEnd ?? $date->copy()->setTime(20, 0);

        $slots = [];
        $current = $start->copy();

        while ($current->copy()->addMinutes($durationMinutes)->lte($end)) {
            $slotEnd = $current->copy()->addMinutes($durationMinutes);

            if (! $this->conflictDetector->hasConflict($current, $slotEnd, $constraints)) {
                $slots[] = [
                    'from' => $current->copy(),
                    'to' => $slotEnd,
                ];
            }

            $current->addMinutes($intervalMinutes);
        }

        return $slots;
    }

    /**
     * Check if a specific time slot is available.
     *
     * @param  array{place_id?: int|null, staff_id?: int|null}  $constraints
     */
    public function isAvailable(
        CarbonInterface $from,
        CarbonInterface $to,
        array $constraints = [],
        ?int $excludeReservationId = null,
    ): bool {
        return ! $this->conflictDetector->hasConflict($from, $to, $constraints, $excludeReservationId);
    }
}
