<?php

namespace Database\Seeders;

use App\Models\Dasawisma;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DasawismaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dasawisma::factory(500)->create();
    }
}
