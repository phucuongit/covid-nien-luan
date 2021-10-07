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
        // \App\Models\User::factory(10)->create();
        Schema::disableForeignKeyConstraints();

        $this->call([
            StatisticSeeder::class,
            RoleTableSeeder::class,
            Vaccine_typeTableSeeder::class,
        ]);
        // Order is important
        $userQuantity = 1532142;
        $this->call(UserTableSeeder::class, false, ['count' => $userQuantity]);
        $this->call(VaccinationTableSeeder::class, false, ['count' => 1732142, 'maxUserId' => $userQuantity]);
        $this->call(Result_testTableSeeder::class, false, ['count' => 1792182, 'maxUserId' => $userQuantity]);

        // Update statistic
        $statistic = Statistic::first();
        $statistic->injected_first_time = Vaccination::where('time', 1)->count();
        $statistic->injected_second_time = Vaccination::where('time', 2)->count();
        $statistic->injected_total_time = Vaccination::all()->count();
        $statistic->save();
    }
}
