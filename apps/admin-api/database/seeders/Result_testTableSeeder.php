<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Result_test;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;
use Faker\Factory as Faker;

class Result_testTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    private $result_testData = [];

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
    
    public function run($count = 10)
    {
        // Remove all current data
        Result_test::truncate();

        // Result_test::factory()->count($count)->create();

        // Seeding
        for ( $i=0; $i < $count ; $i++) {
            $status = rand(0, 10) == 1 ? 'positive' : 'negative';
            $userId = 
                User::where('role_id', 2)->inRandomOrder()->first()->id;
            $userCreate_by = rand(1, 10);
                User::where('role_id', 1)->inRandomOrder()->first()->id;

            // Custom created_at data
            $created_at = Carbon::now()->subDays(rand(0, 90));
            $updated_at = $created_at;

            $result_testData[] = [
                'status' => $status,
                'user_id' => $userId,
                'create_by' => $userCreate_by,
                'created_at' => $created_at
            ];
        }

        // Devide an array into arrays
        $numElements = $count > 100 ? floor($count/100) : 1;
        $chunks = array_chunk($result_testData, $numElements);

        // Insert to DB
        foreach ($chunks as $chunk) {
            Result_test::insert($chunk);
        }
    }
}
