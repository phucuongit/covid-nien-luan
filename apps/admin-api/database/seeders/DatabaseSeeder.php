<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Vaccine_type;
use App\Models\Vaccination;
use App\Models\Result_test;
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
            RoleTableSeeder::class,
            Vaccine_typeTableSeeder::class,
        ]);
        // Order is important
        $this->call(UserTableSeeder::class, false, ['count' => 100]);
        $this->call(VaccinationTableSeeder::class, false, ['count' => 200]);
        $this->call(Result_testTableSeeder::class, false, ['count' => 200]);
    }
}
