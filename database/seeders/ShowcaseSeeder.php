<?php

namespace Database\Seeders;

use App\Domain\Booking\Enums\ReservationStatus;
use App\Domain\Booking\Models\Reservation;
use App\Domain\Finance\Models\Payment;
use App\Domain\IndividualBooking\Models\Staff;
use App\Domain\PlaceBooking\Models\Place;
use App\Domain\Shared\Models\Company;
use App\Domain\Shared\Models\Customer;
use App\Domain\Shared\Models\Review;
use App\Domain\Shared\Models\Service;
use Illuminate\Database\Seeder;

class ShowcaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── CRM ──
        $customers = Customer::factory(25)->create();
        $vipCustomers = Customer::factory(5)->vip()->create();
        $allCustomers = $customers->merge($vipCustomers);

        // ── Reservation System ──
        $companies = Company::all();

        // Update existing companies with enhanced fields
        $companies->each(function (Company $company) {
            $company->update([
                'description' => fake()->paragraph(),
                'email' => fake()->companyEmail(),
                'phone' => fake()->phoneNumber(),
                'website' => fake()->url(),
                'address' => fake()->streetAddress(),
                'city' => fake()->city(),
                'country' => fake()->randomElement(['US', 'UK', 'DE', 'CZ']),
                'is_active' => true,
                'opening_time' => '08:00',
                'closing_time' => '20:00',
            ]);
        });

        // Update existing places with enhanced fields
        Place::all()->each(function (Place $place) {
            $place->update([
                'description' => fake()->sentence(),
                'type' => fake()->randomElement(['court', 'room', 'pool', 'studio', 'field']),
                'capacity' => fake()->randomElement([1, 5, 10, 15, 20, 30]),
                'hourly_rate' => fake()->randomFloat(2, 15, 100),
                'color' => fake()->randomElement(['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4']),
                'is_active' => true,
                'amenities' => fake()->randomElements(['WiFi', 'AC', 'Showers', 'Lockers', 'Parking', 'Sound System', 'Lighting'], fake()->numberBetween(2, 5)),
            ]);
        });

        // Services
        $services = collect();
        $companies->each(function (Company $company) use ($services) {
            $created = Service::factory(fake()->numberBetween(4, 8))
                ->for($company)
                ->create();
            $services->push(...$created);
        });

        // Staff (some with avatar images, some without)
        $avatarIndex = 1;
        $staff = collect();
        $companies->each(function (Company $company) use ($staff, &$avatarIndex) {
            $count = fake()->numberBetween(3, 6);
            foreach (range(1, $count) as $i) {
                $hasAvatar = fake()->boolean(70);
                $created = Staff::factory()
                    ->for($company)
                    ->create([
                        'avatar_url' => $hasAvatar ? "https://i.pravatar.cc/150?img={$avatarIndex}" : null,
                    ]);
                $staff->push($created);
                $avatarIndex++;
            }
        });

        $allServices = Service::all();
        $allStaff = Staff::all();

        // Update existing reservations with new fields
        Reservation::all()->each(function (Reservation $reservation) use ($allCustomers, $allServices, $allStaff) {
            $companyServices = $allServices->where('company_id', $reservation->company_id);
            $companyStaff = $allStaff->where('company_id', $reservation->company_id);
            $status = fake()->randomElement(ReservationStatus::cases());

            $reservation->update([
                'customer_id' => fake()->boolean(60) ? $allCustomers->random()->id : null,
                'service_id' => $companyServices->isNotEmpty() ? $companyServices->random()->id : null,
                'staff_id' => $companyStaff->isNotEmpty() && fake()->boolean(70) ? $companyStaff->random()->id : null,
                'status' => $status,
                'total_price' => fake()->randomFloat(2, 20, 200),
                'guest_name' => fake()->boolean(40) ? fake()->name() : null,
                'guest_email' => fake()->boolean(30) ? fake()->email() : null,
                'guest_phone' => fake()->boolean(20) ? fake()->phoneNumber() : null,
                'notes' => fake()->boolean(20) ? fake()->sentence() : null,
            ]);
        });

        // Reviews (for completed reservations)
        Reservation::where('status', 'completed')
            ->get()
            ->random(min(40, Reservation::where('status', 'completed')->count()))
            ->each(function (Reservation $reservation) use ($allCustomers) {
                Review::factory()->create([
                    'reservation_id' => $reservation->id,
                    'customer_id' => $reservation->customer_id ?? $allCustomers->random()->id,
                ]);
            });

        // Payments (for confirmed/completed reservations)
        Reservation::whereIn('status', ['confirmed', 'checked_in', 'completed'])
            ->get()
            ->each(function (Reservation $reservation) {
                Payment::factory()->create([
                    'reservation_id' => $reservation->id,
                    'customer_id' => $reservation->customer_id,
                    'amount' => $reservation->total_price ?? fake()->randomFloat(2, 20, 200),
                ]);
            });
    }
}
