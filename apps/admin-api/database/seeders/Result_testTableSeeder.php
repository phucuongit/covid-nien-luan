<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Result_test;

class Result_testTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($count = 10)
    {
        Result_test::factory()->count($count)->create();
    }
}
