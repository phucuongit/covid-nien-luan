<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Village;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

// use Buihuycuong\Vnfaker\VNFaker;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */  
    public function definition()
    {
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

        return [
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

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                //'email_verified_at' => null,
            ];
        });
    }
}
