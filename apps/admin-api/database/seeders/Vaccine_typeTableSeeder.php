<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vaccine_type;
use Carbon\Carbon;

class Vaccine_typeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vaccine_type::truncate();
        $data = [
            [
                'name' => 'Pfizer',
                'country' => 'United States',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'AstraZeneca',
                'country' => 'England',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Moderna',
                'country' => 'United States',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Sputnik V',
                'country' => 'Russian',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Sinopharm',
                'country' => 'China',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            // [
            //     'name' => 'Covaxin',
            //     'country' => 'Vietnam',
            // ],
            // [
            //     'name' => 'Janssen',
            //     'country' => 'Netherlands',
            // ],
        ];
        Vaccine_type::insert($data);
    }
}
