<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
| Test
*/




Route::post('/auth/login', [UserController::class, 'login_user']);
Route::post('/auth/admin_registration', [UserController::class, 'create_admin_user']);
//Route::get('authentication/login', ['uses' => 'Authentication/UserController@user_login']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    
    Route::get('/users/get_users', [UserController::class, 'get_users']);
    Route::post('/auth/register', [UserController::class, 'create_user']);
    Route::post('/auth/update_user', [UserController::class, 'update_user']);
});