<?php

namespace Database\Factories;

use App\Models\Vaccination;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

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
        // Schema::disableForeignKeyConstraints();
        // return [
        //     'user_id' => function (array $attributes) {
        //         return User::find($attributes['id'])->id;
        //     }
        //     'create_by' =>  function (array $attributes) {
        //         return User::find($attributes['id'])->id;
        //     }
        // ];
    }
}
