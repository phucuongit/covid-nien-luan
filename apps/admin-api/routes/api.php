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
    Route::post('/auth/register', [RegisterController::class, 'register']);
    Route::post('/auth/login', [LoginController::class, 'login']);
    Route::get('/test',  [LoginController::class, 'test']);
    Route::middleware('auth:api')->group( function () {
        Route::resource('user', UserController::class);
        //api resource
        Route::resource('vaccination', VaccinationController::class);
        Route::resource('result_test', Result_testController::class);
        Route::resource('vaccine_type', Vaccine_typeController::class);

        Route::resource('address/province', ProvinceController::class)->only('index');
        Route::resource('address/district', DistrictController::class)->only('index');
        Route::resource('address/village', VillageController::class)->only('index');
        Route::resource('file/image', ImageController::class)->only(['store','destroy']);
    });
});