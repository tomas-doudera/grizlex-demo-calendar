<?php

namespace App\Domain\PlaceBooking\Services;

use App\Domain\Booking\Services\ConflictDetector;
use App\Domain\PlaceBooking\Models\Place;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class PlaceAvailabilityService
{
    public function __construct(
        private ConflictDetector $conflictDetector,
    ) {}

    /**
     * Check if a place is available at a specific date/time for the given duration.
     */
    public function isAvailable(Place $place, CarbonImmutable $dateTime, int $durationMinutes): bool
    {
        $end = $dateTime->addMinutes($durationMinutes);

        if (! $this->isWithinSchedule($place, $dateTime, $end)) {
            return false;
        }

        return ! $this->conflictDetector->hasConflict($dateTime, $end, [
            'place_id' => $place->id,
        ]);
    }

    /**
     * Get available time slots for a place on a given date.
     *
     * @return Collection<int, array{start: CarbonImmutable, end: CarbonImmutable}>
     */
    public function getAvailableSlots(Place $place, CarbonImmutable $date, int $slotDurationMinutes): Collection
    {
        $dayOfWeek = $this->carbonDayToIso($date);

        $schedules = $place->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->get();

        if ($schedules->isEmpty()) {
            return collect();
        }

        $interval = (int) config('place-booking.default_booking_interval', 60);
        $slots = collect();

        foreach ($schedules as $schedule) {
            $windowStart = $date->setTimeFromTimeString($schedule->start_time);
            $windowEnd = $date->setTimeFromTimeString($schedule->end_time);

            $cursor = $windowStart;

            while ($cursor->addMinutes($slotDurationMinutes)->lte($windowEnd)) {
                $slotEnd = $cursor->addMinutes($slotDurationMinutes);

                if (! $this->conflictDetector->hasConflict($cursor, $slotEnd, [
                    'place_id' => $place->id,
                ])) {
                    $slots->push([
                        'start' => $cursor,
                        'end' => $slotEnd,
                    ]);
                }

                $cursor = $cursor->addMinutes($interval);
            }
        }

        return $slots;
    }

    private function isWithinSchedule(Place $place, CarbonInterface $start, CarbonInterface $end): bool
    {
        $dayOfWeek = $this->carbonDayToIso($start);

        return $place->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->where('start_time', '<=', $start->format('H:i:s'))
            ->where('end_time', '>=', $end->format('H:i:s'))
            ->exists();
    }

    private function carbonDayToIso(CarbonInterface $date): int
    {
        return $date->dayOfWeekIso - 1;
    }
}
