<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Carbon\Carbon;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Remove all current
        Role::truncate();
        
        $data = [
            [
                'name' => 'admin',
            ],
            [
                'name' => 'user',
            ],
        ];
        
        foreach ($data as $key => $value) {
            Role::create($value);
        }
    }
}
