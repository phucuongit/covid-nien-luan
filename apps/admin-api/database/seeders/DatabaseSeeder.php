<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use Database\UserTableSeeder;
// use Database\RoleTableSeeder;
// use Database\Vaccine_typeTableSeeder;

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
        $this->call([
            UserTableSeeder::class,
            RoleTableSeeder::class,
            Vaccine_typeTableSeeder::class,
        ]);
    }
}
