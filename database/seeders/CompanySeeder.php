<?php

namespace Database\Seeders;

use App\Domain\Shared\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::factory(['title' => 'Company 1'])->create();
        Company::factory(['title' => 'Company 2'])->create();
        Company::factory(['title' => 'Company 3'])->create();
    }
}
