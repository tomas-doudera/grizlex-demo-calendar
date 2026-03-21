<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::query()->first();

        if (! $company) {
            return;
        }

        $services = [
            [
                'title' => 'Personal Training',
                'description' => 'One-on-one coaching tailored to your goals.',
                'duration_minutes' => 60,
                'price' => 65.00,
                'color' => '#f59e0b',
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'title' => 'Yoga Class',
                'description' => 'Guided group session focusing on mobility and breath.',
                'duration_minutes' => 60,
                'price' => 18.00,
                'color' => '#8b5cf6',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'HIIT Session',
                'description' => 'High-intensity interval training in a small group.',
                'duration_minutes' => 45,
                'price' => 22.00,
                'color' => '#ef4444',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Sports Massage',
                'description' => 'Short recovery massage after training.',
                'duration_minutes' => 30,
                'price' => 40.00,
                'color' => '#10b981',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Nutrition Consultation',
                'description' => 'Initial assessment and meal planning tips.',
                'duration_minutes' => 45,
                'price' => 55.00,
                'color' => '#3b82f6',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($services as $service) {
            Service::factory()->create([
                'company_id' => $company->id,
                ...$service,
            ]);
        }
    }
}
