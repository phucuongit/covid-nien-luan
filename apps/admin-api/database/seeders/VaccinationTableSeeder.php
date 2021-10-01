<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vaccination;
use App\Models\Result_test;
use App\Models\Vaccine_type;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;
use Faker\Factory as Faker;

class VaccinationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    private $vaccinationData = [];

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
        $this->faker = Faker::create();
    }

    public function run($count)
    {
        // Remove all current data
        Vaccination::truncate();

        // Vaccination::factory()->count($count)->create();

        // Seeding
        for ( $i=0; $i < $count ; $i++) {
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

            $vaccinationData[] = [
                'user_id' => $userIds,
                'create_by' => $userCreateIds,
                'vaccine_type_id' => $vaccineTypeIds,
                'time' => $time,
                'created_at' => $created_at,
                'updated_at' => $updated_at
            ];
        }

        // Devide an array into arrays
        $numElements = $count > 100 ? floor($count/100) : 1;
        $chunks = array_chunk($vaccinationData, $numElements);

        // Insert to DB
        foreach ($chunks as $chunk) {
            Vaccination::insert($chunk);
        }
    }
}
