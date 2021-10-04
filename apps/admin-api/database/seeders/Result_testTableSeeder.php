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
    
    public function run($count = 10, $maxUserId)
    {
        // Remove all current data
        Result_test::truncate();

        // Result_test::factory()->count($count)->create();

        // Seeding
        for ( $i=0; $i < $count ; $i++) {
            $status = rand(0, 10) == 1 ? 'positive' : 'negative';
            $userId = rand(1, $maxUserId);
            $userCreate_by = 1;

            // Custom created_at data
            $created_at = Carbon::now()->subDays(rand(0, 60));
            $updated_at = $created_at;

            $result_testData[] = [
                'status' => $status,
                'user_id' => $userId,
                'create_by' => $userCreate_by,
                'updated_at' => $updated_at
            ];
        }

        // Devide an array into arrays
        $numElements = $count > 500 ? floor($count/500) : 1;
        $chunks = array_chunk($result_testData, $numElements);

        // Insert to DB
        foreach ($chunks as $chunk) {
            Result_test::insert($chunk);
        }
    }
}
