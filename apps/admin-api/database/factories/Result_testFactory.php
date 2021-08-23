<?php

namespace Database\Factories;

use App\Models\Result_test;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class Result_testFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Result_test::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $status = rand(0,100) < 20 ? 'positive' : 'negative';
        $userId = 
            User::where('role_id', 2)->inRandomOrder()->first()->id;
        $userCreate_by = 
            User::where('role_id', 1)->inRandomOrder()->first()->id;
        return [
            'status' => $status,
            'user_id' => $userId,
            'create_by' => $userCreate_by,
        ];
    }
}
