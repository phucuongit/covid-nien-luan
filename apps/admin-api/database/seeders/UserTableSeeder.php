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
        $password = 
            Hash::make('123123');

        for ($i = 0; $i < $count; $i++) {

            // Create fake id card 
            $identity_card = 
                $this->faker->unique()->randomNumber(9, true);

            $fullname =
                rand(0,1) == 1 ? 
                vnfaker()->fullname($word = 3)
                : vnfaker()->fullname($word = 4);

            $birthday = 
                $this->faker->dateTime('-5 years'); // -5 year from now

            $gender = 
                rand(0,1);

            $username = 
                'user'
                .$identity_card;

            $village_id = 
                rand(1, 10603);

            $address = 
                'Đường '
                .rand(1,9)
                .rand(1,9)
                .chr(rand(65,90)); // A-Z

            $phone = 
                substr(vnfaker()->mobilephone($numbers = 10), 0, 3)
                .$this->faker->unique()->randomNumber(7, true);

            $role_id = 2;

            $social_insurance =
                chr(rand(65,90))
                .chr(rand(65,90))
                .$identity_card
                .$this->faker->randomNumber(4, true);

            // Custom created_at
            $created_at = Carbon::now()->subDays(rand(60, 120));
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
        $numElements = $count > 500 ? floor($count/500) : 1;
        $chunks = array_chunk($userData, $numElements);

        // Insert to DB
        foreach ($chunks as $chunk) {
            User::insert($chunk);
        }
    }
}
