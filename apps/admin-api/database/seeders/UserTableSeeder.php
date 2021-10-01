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

        // User::factory()->count($count)->create();

        // User seeding
        for ($i = 0; $i < $count; $i++) {
            // Create fake id card 
            $identity_card = 
                Str::padLeft(strval(rand(1,63)), 3, "0") // Provinces type: 0xx
                .$this->faker->randomNumber(1) // Gender
                .$this->faker->randomNumber(2, true) // Birthday code
                .$this->faker->unique()->randomNumber(6, true); // Random unique number

            $fullname = 
                rand(0,1) == 1 ? 
                vnfaker()->fullname($word = 3) : 
                vnfaker()->fullname($word = 4);

            $birthday = 
                $this->faker->dateTime('-5 years'); // -5 year from now

            // username = name + last 6 numbers indetity card
            $username = 
                vnfaker()->vnToString(substr($fullname, strrpos($fullname, ' ') + 1))
                .substr($identity_card, -6);

            $gender = 
                rand(0,1);

            $password = 
                Hash::make('123123');

            $village_id =
                Village::inRandomOrder()->first()->id;

            $address = 
                'Đường '
                .rand(0,99)
                .chr(rand(65,90)); //A-Z

            $phone = 
                vnfaker()->mobilephone($numbers = 10);

            $role_id = 2;

            $social_insurance = 
                chr(rand(65,90)) // String code
                .chr(rand(65,90))
                .$this->faker->randomNumber(9, true);

            // Custom created_at
            $created_at = Carbon::now()->subDays(rand(90, 180));
            $updated_at = $created_at;

            $userData[] = [
                'identity_card' => $identity_card,
                'social_insurance' => $social_insurance,
                'username' => $username,
                'password' => $password,
                'fullname' => $fullname,
                'birthday' => $birthday,
                'gender' => $gender,
                'address' => $address,
                'phone' => $phone,
                'village_id' => $village_id,
                'role_id' => $role_id,
                'created_at' => $created_at,
                'updated_at' => $updated_at
            ];
        }

        // Devide an array into arrays
        $numElements = $count > 100 ? floor($count/100) : 1;
        $chunks = array_chunk($userData, $numElements);

        // Insert to DB
        foreach ($chunks as $chunk) {
            User::insert($chunk);
        }
    }
}
