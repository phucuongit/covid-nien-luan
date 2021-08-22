<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Vaccine_type;

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

        //Factory With custom quantity
        User::factory()->count(10)->create();

        //Seeder with static quantity
        $this->call([
            RoleTableSeeder::class,
            Vaccine_typeTableSeeder::class,
        ]);
    }
}
