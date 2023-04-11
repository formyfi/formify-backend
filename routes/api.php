<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    UserController,StationController, PartController, ChecklistController, FileController, TaskController, TimelineController
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
// Route::post('/auth/logout', [UserController::class, 'logout']);

Route::post('/file/upload', [FileController::class, 'upload']);



Route::group(['middleware' => ['auth:sanctum']], function(){
    //logout
   Route::post('/auth/logout', [UserController::class, 'logout']);

    //Users Get
    Route::get('/users/get_users', [UserController::class, 'get_users']);
    
   // Users Post
   Route::post('/users/create_user', [UserController::class, 'create_user']);
   Route::post('/users/update_user', [UserController::class, 'update_user']);
   Route::post('/users/delete_user', [UserController::class, 'delete_user']);

   // Stations Get
    Route::get('/stations/get_station_list', [StationController::class, 'get_station_list']);
   
   // Station Post   
    Route::post('/stations/upsert_station', [StationController::class, 'upsert_station']);
    Route::post('/stations/delete_station', [StationController::class, 'delete_station']); 

    //Parts Get
    Route::get('/parts/get_part_list', [PartController::class, 'get_part_list']);
    Route::get('/parts/get_parts_by_station', [PartController::class, 'get_parts_by_station']);
    Route::get('/parts/get_part_vnumbers', [PartController::class, 'get_part_vnumbers']);

    //Parts Post
    Route::post('/parts/upsert_part', [PartController::class, 'upsert_part']);
    Route::post('/parts/delete_part', [PartController::class, 'delete_part']);

    //Checklists Get
    Route::get('/checklist/get_checklists', [ChecklistController::class, 'get_checklists']);
    Route::get('/checklist/get_templates', [ChecklistController::class, 'get_templates']);

    //Checklists Posts
    Route::post('/checklist/upsert_checklist', [ChecklistController::class, 'upsert_checklist']);
    Route::post('/checklist/upsert_checklist_form', [ChecklistController::class, 'upsert_checklist_form']);
    Route::post('/checklist/upsert_checklist_form_template', [ChecklistController::class, 'upsert_checklist_form_template']);
    Route::post('/checklist/delete_checklist', [ChecklistController::class, 'delete_checklist']);

    //Tasks Get
    Route::get('/tasks/get_task_form', [TaskController::class, 'get_task_form']);
    Route::get('/tasks/get_tasklists', [TaskController::class, 'get_tasklists']);
   
     //Tasks Post
    Route::post('/tasks/update_task_form', [TaskController::class, 'update_task_form']);
    

    // create timelines
    Route::post('/timelines/upload', [TimelineController::class, 'store']);
    // list timelines
    Route::get('/timelines/get_vnum_timline', [TimelineController::class, 'get_vnum_timline']);

});




