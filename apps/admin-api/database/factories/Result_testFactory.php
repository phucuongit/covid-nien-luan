<?php

namespace Database\Factories;

use App\Models\Result_test;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

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
        $status = rand(0, 10) == 1 ? 'positive' : 'negative';
        $userId = 
            User::where('role_id', 2)->inRandomOrder()->first()->id;
        $userCreate_by = rand(1, 10);
            User::where('role_id', 1)->inRandomOrder()->first()->id;

        // Custom created_at data
        $created_at = Carbon::now()->subDays(rand(0, 90));
        $updated_at = $created_at;

        return [
            'status' => $status,
            'user_id' => $userId,
            'create_by' => $userCreate_by,
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ];
    }
}
