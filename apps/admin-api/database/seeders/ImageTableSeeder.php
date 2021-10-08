<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Image;
use Carbon\Carbon;

class ImageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($maxImageableId = 1492182)
    {
        // Remove all current data
        Image::truncate();

        $imageUrlData = [
            'user' => 'https://cdn.pixabay.com/photo/2016/08/08/09/17/avatar-1577909_640.png',
            'vaccination' => 'https://tuyengiao.vn/Uploads/2020/12/23/27/cuoc-chien-chong-dai-dich-covid-19-duoi-goc-nhin-chinh-tri-hoc.jpg',
            'result_test' => 'https://tuyengiao.vn/Uploads/2020/12/23/27/cuoc-chien-chong-dai-dich-covid-19-duoi-goc-nhin-chinh-tri-hoc.jpg'
        ];

        $imageData = [];
        $now = Carbon::now();

        for ($i = 0; $i < $maxImageableId; $i++) { 
            // For user
            $imageData[] = [
                'type' => 'avatar',
                'url' => $imageUrlData['user'],
                'imageable_id' => $i + 1,
                'imageable_type' => 'user',
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // For vaccination before
            $imageData[] = [
                'type' => 'vaccination_before',
                'url' => $imageUrlData['vaccination'],
                'imageable_id' => $i + 1,
                'imageable_type' => 'vaccination',
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // For vaccination after
            $imageData[] = [
                'type' => 'vaccination_after',
                'url' => $imageUrlData['vaccination'],
                'imageable_id' => $i + 1,
                'imageable_type' => 'vaccination',
                'created_at' => $now,
                'updated_at' => $now,
            ];

           // For vaccination before
           $imageData[] = [
            'type' => 'result_test_before',
            'url' => $imageUrlData['result_test'],
            'imageable_id' => $i + 1,
            'imageable_type' => 'result_test',
            'created_at' => $now,
            'updated_at' => $now,
            ];

            // For result_test after
            $imageData[] = [
                'type' => 'result_test_after',
                'url' => $imageUrlData['result_test'],
                'imageable_id' => $i + 1,
                'imageable_type' => 'result_test',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Devide an array into arrays
        $numElements = floor($maxImageableId/1000) > 1 ? floor($maxImageableId/1000) : 1;

        // Insert to DB
        foreach (array_chunk($imageData, $numElements) as $chunk) {
            Image::insert($chunk);
        }

    }
}
