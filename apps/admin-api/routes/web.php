<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BaseController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function(){
    return response()->json( [
        'success' => false,
        'code' => 404,
        'message' => 'Nothing to show here',
    ], 404);
});

Route::get('unauthorised', function () {
    return response()->json( [
        'success' => false,
        'code' => 401,
        'message' => 'Your are not logged in',
    ], 401);
})->name('unauthorised-page');
