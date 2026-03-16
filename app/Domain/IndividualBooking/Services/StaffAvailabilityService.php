<?php

namespace App\Domain\IndividualBooking\Services;

use App\Domain\Booking\Services\ConflictDetector;
use App\Domain\IndividualBooking\Models\Staff;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class StaffAvailabilityService
{
    public function __construct(
        private ConflictDetector $conflictDetector,
    ) {}

    /**
     * Check if a staff member is available at a specific date/time for the given duration.
     */
    public function isAvailable(Staff $staff, CarbonImmutable $dateTime, int $durationMinutes): bool
    {
        $end = $dateTime->addMinutes($durationMinutes);

        if (! $this->isWithinSchedule($staff, $dateTime, $end)) {
            return false;
        }

        if ($this->isDuringBreak($staff, $dateTime, $end)) {
            return false;
        }

        $bufferMinutes = (int) config('individual-booking.buffer_between_appointments', 0);
        $checkFrom = $dateTime->subMinutes($bufferMinutes);
        $checkTo = $end->addMinutes($bufferMinutes);

        return ! $this->conflictDetector->hasConflict($checkFrom, $checkTo, [
            'staff_id' => $staff->id,
        ]);
    }

    /**
     * Get available time slots for a staff member on a given date.
     *
     * @return Collection<int, array{start: CarbonImmutable, end: CarbonImmutable}>
     */
    public function getAvailableSlots(Staff $staff, CarbonImmutable $date, int $slotDurationMinutes): Collection
    {
        $dayOfWeek = $this->carbonDayToIso($date);

        $schedules = $staff->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->get();

        if ($schedules->isEmpty()) {
            return collect();
        }

        $bufferMinutes = (int) config('individual-booking.buffer_between_appointments', 0);
        $slots = collect();

        foreach ($schedules as $schedule) {
            $windowStart = $date->setTimeFromTimeString($schedule->start_time);
            $windowEnd = $date->setTimeFromTimeString($schedule->end_time);

            $cursor = $windowStart;

            while ($cursor->addMinutes($slotDurationMinutes)->lte($windowEnd)) {
                $slotEnd = $cursor->addMinutes($slotDurationMinutes);

                if (! $this->isDuringBreak($staff, $cursor, $slotEnd)) {
                    $checkFrom = $cursor->subMinutes($bufferMinutes);
                    $checkTo = $slotEnd->addMinutes($bufferMinutes);

                    if (! $this->conflictDetector->hasConflict($checkFrom, $checkTo, [
                        'staff_id' => $staff->id,
                    ])) {
                        $slots->push([
                            'start' => $cursor,
                            'end' => $slotEnd,
                        ]);
                    }
                }

                $cursor = $slotEnd->addMinutes($bufferMinutes);
            }
        }

        return $slots;
    }

    /**
     * Check if the requested time falls within the staff's working schedule.
     */
    private function isWithinSchedule(Staff $staff, CarbonInterface $start, CarbonInterface $end): bool
    {
        $dayOfWeek = $this->carbonDayToIso($start);

        return $staff->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->where('start_time', '<=', $start->format('H:i:s'))
            ->where('end_time', '>=', $end->format('H:i:s'))
            ->exists();
    }

    /**
     * Check if the requested time overlaps with any break.
     */
    private function isDuringBreak(Staff $staff, CarbonInterface $start, CarbonInterface $end): bool
    {
        $dayOfWeek = $this->carbonDayToIso($start);
        $startTime = $start->format('H:i:s');
        $endTime = $end->format('H:i:s');

        return $staff->breaks()
            ->where(function ($query) use ($start, $dayOfWeek): void {
                $query->whereDate('date', $start->toDateString())
                    ->orWhere('day_of_week', $dayOfWeek);
            })
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->exists();
    }

    /**
     * Convert Carbon's day of week (1=Monday..7=Sunday) to ISO (0=Monday..6=Sunday).
     */
    private function carbonDayToIso(CarbonInterface $date): int
    {
        return $date->dayOfWeekIso - 1;
    }
}
