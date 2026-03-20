<?php

namespace Database\Factories;

use App\Enums\ReservationStatus;
use App\Models\Company;
use App\Models\Place;
use App\Models\Reservation;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fromTime = Carbon::instance(fake()->dateTimeBetween('now', '+7 days'))
            ->setHour(fake()->numberBetween(8, 15))
            ->setMinute(fake()->randomElement([0, 15, 30, 45]))
            ->setSecond(0);
        $toTime = (clone $fromTime)->addMinutes(60);

        return [
            'company_id' => Company::factory(),
            'place_id' => Place::factory(),
            'staff_id' => Staff::factory(),
            'from_time' => $fromTime,
            'to_time' => $toTime,
            'status' => ReservationStatus::Pending,
            'guest_name' => null,
            'guest_email' => null,
            'guest_phone' => null,
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn (): array => [
            'status' => ReservationStatus::Confirmed,
            'confirmed_at' => now(),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (): array => [
            'status' => ReservationStatus::Cancelled,
            'cancelled_at' => now(),
            'cancellation_reason' => fake()->sentence(),
        ]);
    }

    public function forUser(): static
    {
        return $this->state(fn (): array => [
            'user_id' => User::factory(),
            'guest_name' => null,
            'guest_email' => null,
            'guest_phone' => null,
        ]);
    }
}
