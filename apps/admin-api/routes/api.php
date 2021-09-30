<?php
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\API\Vaccination\VaccinationController;
use App\Http\Controllers\API\Result_test\Result_testController;
use App\Http\Controllers\API\Vaccine_type\Vaccine_typeController;
use App\Http\Controllers\API\Address\ProvinceController;
use App\Http\Controllers\API\Address\DistrictController;
use App\Http\Controllers\API\Address\VillageController;
use App\Http\Controllers\API\Upload\ImageController;
use App\Http\Controllers\API\User\Look_upController;
use App\Http\Controllers\API\AdminProfile\AdminProfileController;
use App\Http\Controllers\API\Statistic\StatisticController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('v1')->group(function () {
    // api for authentication
    Route::post('/auth/register', [RegisterController::class, 'register']);
    Route::post('/auth/login', [LoginController::class, 'login']);
    // api for users
    Route::resource('look_up', Look_upController::class)->only('show');
    // api for statistic information
    Route::resource('statistic', StatisticController::class)->only('index');
    // api for admin
    Route::middleware('auth:api')->group( function () {
        // api for admin profile
        Route::get('profile', [AdminProfileController::class, 'index']);
        Route::put('profile/update', [AdminProfileController::class, 'update']);
        Route::delete('profile/destroy', [AdminProfileController::class, 'destroy']);

        // api resource
        Route::resource('user', UserController::class);
        Route::resource('vaccination', VaccinationController::class);
        Route::resource('result_test', Result_testController::class);
        Route::resource('vaccine_type', Vaccine_typeController::class);

        // api for location
        Route::resource('address/province', ProvinceController::class)->only('index');
        Route::resource('address/district', DistrictController::class)->only('index');
        Route::resource('address/village', VillageController::class)->only('index');

        // api for upload image
        Route::resource('file/image', ImageController::class)->only(['store','destroy']);
    });
});