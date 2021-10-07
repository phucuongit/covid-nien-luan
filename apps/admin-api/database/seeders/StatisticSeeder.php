<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Statistic;

class StatisticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Remove all current data
        Statistic::truncate();
        // Setup for statistic
        Statistic::create(
            [
                'injected_first_time' => 0,
                'injected_second_time' => 0,
                'injected_total_time' => 0
            ]
        );
    }
}
