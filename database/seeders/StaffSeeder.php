<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Staff;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::query()->first();

        if (! $company) {
            return;
        }

        Staff::factory()->count(10)->create([
            'company_id' => $company->id,
        ]);
    }
}
