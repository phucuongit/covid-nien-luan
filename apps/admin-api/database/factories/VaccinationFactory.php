<?php

namespace Database\Factories;

use App\Models\Vaccination;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Vaccine_type;

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
        Schema::disableForeignKeyConstraints();
        //Get id from user
        $userIds = 
            User::inRandomOrder()->first()->id;
        $userCreateIds = 
            User::where('role_id', 1)->inRandomOrder()->first()->id;
        // 40% vaccine is Astra
        $vaccineTypeIds = rand(1, 100) > 60 ?
            Vaccine_type::where('name', 'AstraZeneca')->first()->id :
            Vaccine_type::inRandomOrder()->first()->id;
        return [
            'user_id' => $userIds,
            'create_by' => $userCreateIds,
            'vaccine_type_id' => $vaccineTypeIds,
        ];
    }
}
