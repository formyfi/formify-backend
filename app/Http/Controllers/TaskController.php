<?php

namespace App\Http\Controllers;
use App\Services\TaskService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\{
    Task,
  };

class TaskController extends Controller
{
    public function get_task_list(Request $request){
        $org_id = $request->input('org_id');

        if(empty($org_id)) return response()->json(['success' => false]);
        
        $list = TaskService::get_task_list((int)$org_id);

        if(!empty($list)){
            return response()->json(['success' => true, 'part_list' => $list]);
        } else return response()->json(['success' => false]);
    }

    public static function get_task_form(Request $request){
        $station_value = $request->input('station_value');
        $part_value = $request->input('part_value');
        $v_number = $request->input('v_number');
        
        if(empty($station_value) || empty($part_value)) return response()->json(['success' => false]);

        $form_data = TaskService::get_task_form((int)$station_value, (int)$part_value, (int)$v_number);

        if(!empty($form_data)){
            return response()->json(['success' => true, 'form_data' => $form_data]);
        } else return response()->json(['success' => false]);

    }
    public function upsert_part(Request $request){
        $id = $request->input('id');
        $name = $request->input('name');
        $description = $request->input('description');
        $station_value = $request->input('station_value');
        $v_numbers = $request->input('v_numbers');
        $org_id = $request->input('org_id');

        if(empty($name)) return response()->json(['success' => true]);

        if(empty($id))  TaskService::insert_part(['name' => $name, 'description' => $description, 'org_id' => $org_id, 'station_id' => $station_value, 'v_numbers' => $v_numbers]);
        else TaskService::update_part(['name' => $name, 'description' => $description, 'station_id' => $station_value, 'v_numbers' => $v_numbers], ['id' => $id]);
        
        $list = TaskService::get_task_list((int)$org_id);
        if(!empty($list)){
            return response()->json(['success' => true, 'part_list' => $list]);
        } else return response()->json(['success' => true]);
    }

    public static function delete_part (Request $request){
        $id = $request->input('id');
        $org_id = $request->input('org_id');

        if(empty($id)) return response()->json(['success' => true]);
        TaskService::delete_part_by_id(['id' => $id]);

        $list = TaskService::get_task_list((int)$org_id);
        if(!empty($list)){
            return response()->json(['success' => true, 'part_list' => $list]);
        } else return response()->json(['success' => true]);
    }
}
