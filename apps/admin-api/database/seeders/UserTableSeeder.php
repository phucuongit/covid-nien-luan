<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(int $count = 10)
    {
        User::truncate();
        //User admin
        $data = [
            [
                'identity_card' => 999999999,
                'social_insurance' => 999999999,
                'fullname' => 'Nien luan Manager',
                'gender' => '1',
                'username' => 'nienluan',
                'password' => Hash::make('123123'),
                'address' => 'Hau Giang',
                'village_id' => '1',
                'role_id' => '1',
            ],
        ];
        User::insert($data);
        User::factory()->count($count)->create();
    }
}
