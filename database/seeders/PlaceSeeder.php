<?php

namespace Database\Seeders;

use App\Models\Place;
use Illuminate\Database\Seeder;

class PlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Place::factory(['company_id' => 1, 'title' => 'C1 slot 1', 'short_title' => 'C1 S1'])->create();
        Place::factory(['company_id' => 1, 'title' => 'C1 slot 2', 'short_title' => 'C1 S2'])->create();
        Place::factory(['company_id' => 1, 'title' => 'C1 slot 3', 'short_title' => 'C1 S3'])->create();

        Place::factory(['company_id' => 2, 'title' => 'C2 slot 1', 'short_title' => 'C2 S1'])->create();
        Place::factory(['company_id' => 2, 'title' => 'C2 slot 2', 'short_title' => 'C2 S2'])->create();
        Place::factory(['company_id' => 2, 'title' => 'C2 slot 3', 'short_title' => 'C2 S3'])->create();

        Place::factory(['company_id' => 3, 'title' => 'C3 slot 1', 'short_title' => 'C3 S1'])->create();
        Place::factory(['company_id' => 3, 'title' => 'C3 slot 2', 'short_title' => 'C3 S2'])->create();
        Place::factory(['company_id' => 3, 'title' => 'C3 slot 3', 'short_title' => 'C3 S3'])->create();
    }
}
