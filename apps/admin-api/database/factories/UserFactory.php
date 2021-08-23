<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

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
        //Create fake id card 
        $idCard = Str::padLeft(strval(rand(1,63)), 3, "0") //Provinces type: 0xx
                .strval(rand(1,9)) //Gender
                .$this->faker->randomNumber(2, true) //Birthday code
                .$this->faker->unique()->randomNumber(6, true); // Random
    
        $fullname = 
            rand(0,1) == 1 ? vnfaker()->fullname($word = 3) : vnfaker()->fullname($word = 4);
        $birthday = 
            $this->faker->dateTime('-10 years'); // -10 year from now
        $username =
            vnfaker()->vnToString(substr($fullname, strrpos($fullname, ' ') + 1))
            .substr($idCard, -6);
        $gender = rand(0,1);
        $password = Hash::make('123123');
        $address = vnfaker()->city();
        $phone = vnfaker()->mobilephone($numbers = 10);
        $avatar = 'none';
        $role_id = 2;
        return [
            'identify_card' => $idCard,
            'fullname' => $fullname,
            'birthday' => $birthday,
            'gender' => $gender,
            'avatar' => $avatar,
            'username' => $username,
            'password' => $password,
            'address' => $address,
            'phone' => $phone,
            'role_id' => $role_id,
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
