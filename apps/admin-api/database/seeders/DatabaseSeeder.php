<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Vaccine_type;
use App\Models\Vaccination;
use App\Models\Result_test;
use App\Models\Statistic;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        
        $userQuantity = 1332142;
        $vaccinationQuantity = 1432142;
        $result_testQuantity = 1492182;

        /* -----------Order is important-----------*/
        $this->call([
            StatisticSeeder::class,
            RoleTableSeeder::class,
            Vaccine_typeTableSeeder::class,
        ]);

        /* -----------At least 5gb ram-----------*/
        $this->call(UserTableSeeder::class, false, ['count' => $userQuantity]);
        $this->call(VaccinationTableSeeder::class, false, ['count' => $vaccinationQuantity, 'maxUserId' => $userQuantity]);
        $this->call(Result_testTableSeeder::class, false, ['count' => $result_testQuantity, 'maxUserId' => $userQuantity]);

        /* -----------Update statistic-----------*/
        $statistic = Statistic::first();
        $statistic->injected_first_time = Vaccination::where('time', 1)->count();
        $statistic->injected_second_time = Vaccination::where('time', 2)->count();
        $statistic->injected_total_time = Vaccination::all()->count();
        $statistic->save();

        /* -----------Image seeder-----------*/
        $maxImageableId = $userQuantity;
        if ($vaccinationQuantity > $maxImageableId) 
            $maxImageableId = $vaccinationQuantity;
        if ($result_testQuantity > $maxImageableId) 
            $maxImageableId = $result_testQuantity;
        $this->call(ImageTableSeeder::class, false, ['maxImageableId' => $maxImageableId]);
    }
}
