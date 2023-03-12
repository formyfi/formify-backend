<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::post('/auth/register', [AuthController::class, 'create_user']);
Route::post('/auth/login', [AuthController::class, 'login_user']);

//Route::get('authentication/login', ['uses' => 'Authentication/AuthController@user_login']);

// Route::group(['middleware' => ['auth:sanctum'], function(){

// }]);