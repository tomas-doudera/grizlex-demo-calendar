<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::factory()->create([
            'title' => 'Fitness Club',
            'email' => 'info@fitnessclub.com',
            'phone' => '+420 123 456 789',
            'address' => '123 Main St',
            'city' => 'New York',
            'postal_code' => '10001',
            'country' => 'United States',
            'timezone' => 'America/New_York',
            'currency' => 'USD',
        ]);
    }
}
