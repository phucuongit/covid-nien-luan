<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vaccine_type;

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
            ],
            [
                'name' => 'AstraZeneca',
                'country' => 'England',
            ],
            [
                'name' => 'Moderna',
                'country' => 'United States',
            ],
            [
                'name' => 'Sputnik V',
                'country' => 'Russian',
            ],
            [
                'name' => 'Sinopharm',
                'country' => 'China',
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
