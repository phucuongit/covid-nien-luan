<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vaccination;

class VaccinationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($count)
    {
        Vaccination::truncate();
        Vaccination::factory()->count($count)->create();
        //Update users have vaccination count > 3 
        // $data = Vaccination::select('vaccinations.*, COUNT(id)')
        //     ->groupBy('user_id')
        //     ->havingRaw('COUNT(id) > ?', [25])
        //     ->get();
        // dd($data);
    }
}
