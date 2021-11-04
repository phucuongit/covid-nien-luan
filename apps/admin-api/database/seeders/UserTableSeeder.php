<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Village;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;
use Faker\Factory as Faker;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    private $userData = [];
    
    /**
     * The current Faker instance.
     *
     * @var Faker\Factory
     */
    protected $faker;

    /**
     * Create a new seeder instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->faker = Faker::Create();
    }
    
    public function run(int $count = 10)
    {
        // Remove all current data
        User::truncate();

        // Admin default
        $data = [
            'identity_card' => 999999999,
            'social_insurance' => 999999999,
            'fullname' => 'Nien luan Manager',
            'gender' => '1',
            'username' => 'nienluan',
            'password' => Hash::make('123123'),
            'address' => 'Hau Giang',
            'village_id' => '1',
            'role_id' => '1',
        ];
        User::create($data);

        // User seeding
        for ($i = 0; $i < $count; $i++) {
            
            // Rand unique number
            $numRand = $this->faker->unique()->randomNumber(7, true);

            // Create fake id card 
            $identity_card = 
                $numRand.$this->faker->randomNumber(2, true);

            $fullname =
                $i % 2 == 0 ? 
                vnfaker()->fullname($word = 3)
                : vnfaker()->fullname($word = 4);

            $birthday = 
                $this->faker->dateTime('-5 years'); // -5 year from now

            $gender = 
                rand(0,1);

            $village_id = 
                rand(1, 10603);

            $address = 
                'Đường '
                .rand(1,9)
                .rand(1,9)
                .chr(rand(65,90)); // A-Z

            $phone = 
                substr(vnfaker()->mobilephone($numbers = 10), 0, 3)
                .$numRand;

            $role_id = 2;

            $social_insurance =
                chr(rand(65,90))
                .chr(rand(65,90))
                .$numRand
                .substr($numRand, 0, 6);

            // Custom created_at
            $created_at = Carbon::now()->subDays(rand(60, 120));

            $userData[] = [
                'identity_card' => $identity_card,
                'social_insurance' => $social_insurance,
                'fullname' => $fullname,
                'birthday' => $birthday,
                'gender' => $gender,
                'address' => $address,
                'phone' => $phone,
                'village_id' => $village_id,
                'role_id' => $role_id,
                'created_at' => $created_at,
                'updated_at' => $created_at
            ];
        }

        // Devide an array into arrays
        $numElements = floor($count/100) > 1 ? floor($count/100) : 1;

        // Insert to DB
        foreach (array_chunk($userData, $numElements) as $chunk) {
            User::insert($chunk);
        }
    }
}
