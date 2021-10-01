<?php

namespace Database\Factories;

use App\Models\Vaccination;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Vaccine_type;
use Carbon\Carbon;

class VaccinationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vaccination::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        //Get id from user
        $userIds =
            User::inRandomOrder()->first()->id;

        $userCreateIds =
            User::where('role_id', 1)->inRandomOrder()->first()->id;

        // > 40% vaccine type is Astra
        $vaccineTypeIds = rand(1, 10) > 6 ? 
            Vaccine_type::where('name', 'AstraZeneca')->first()->id :
            Vaccine_type::inRandomOrder()->first()->id;

        $time = rand(1,2);

        // Custom created_at
        $created_at = Carbon::now()->subDays(rand(0, 90));
        $updated_at = $created_at;
        
        return [
            'user_id' => $userIds,
            'create_by' => $userCreateIds,
            'vaccine_type_id' => $vaccineTypeIds,
            'time' => $time,
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ];
    }
}
