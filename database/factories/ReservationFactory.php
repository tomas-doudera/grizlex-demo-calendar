<?php

namespace Database\Factories;

use App\Enums\ReservationStatus;
use App\Models\Company;
use App\Models\Place;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $fromTime = fake()->dateTimeBetween('now', '+14 days');
        $toTime = (clone $fromTime)->modify('+60 minutes');

        return [
            'company_id' => Company::factory(),
            'place_id' => Place::factory(),
            'from_time' => $fromTime,
            'to_time' => $toTime,
            'status' => ReservationStatus::Pending,
            'guest_name' => fake()->name(),
            'guest_email' => fake()->safeEmail(),
            'guest_phone' => fake()->phoneNumber(),
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
