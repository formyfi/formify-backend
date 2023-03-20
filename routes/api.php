<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    UserController,StationController, PartController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
| aaa
*/




Route::post('/auth/login', [UserController::class, 'login_user']);
Route::post('/auth/admin_registration', [UserController::class, 'create_admin_user']);
//Route::get('authentication/login', ['uses' => 'Authentication/UserController@user_login']);
Route::get('/stations/get_station_list', [StationController::class, 'get_station_list']);
Route::post('/stations/upsert_station', [StationController::class, 'upsert_station']);
Route::post('/stations/delete_station', [StationController::class, 'delete_station']);

Route::get('/parts/get_part_list', [PartController::class, 'get_part_list']);
Route::post('/parts/upsert_part', [PartController::class, 'upsert_part']);
Route::post('/parts/delete_part', [PartController::class, 'delete_part']);


Route::post('/users/create_user', [UserController::class, 'create_user']);
Route::post('/users/update_user', [UserController::class, 'update_user']);
Route::post('/users/delete_user', [UserController::class, 'delete_user']);
Route::get('/users/get_users', [UserController::class, 'get_users']);




// Route::group(['middleware' => ['auth:sanctum']], function(){
//     //Users Get
//     // Route::get('/users/get_users', [UserController::class, 'get_users']);
    
//     //Users Post
//     // Route::post('/users/create_user', [UserController::class, 'create_user']);
//     // Route::post('/users/update_user', [UserController::class, 'update_user']);

//     //Stations Get
//    // Route::get('/stations/get_station_list', [StationController::class, 'get_station_list']);

 

// });