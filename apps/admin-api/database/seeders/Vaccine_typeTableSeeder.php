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
        // Remove all current data
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
            [
                'name' => 'Janssen',
                'country' => 'Netherlands',
            ],
            [
                'name' => 'Covaxin',
                'country' => 'Vietnam',
            ],
        ];
        foreach ($data as $key => $value) {
            Vaccine_type::create($value);
        }
    }
}
