<?php

namespace App\Http\Controllers;
use App\Services\ChecklistService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChecklistController extends Controller
{
    public function get_checklists(Request $request){
        $org_id = $request->input('org_id');

        if(empty($org_id)) return response()->json(['success' => false]);
        
        $list = ChecklistService::get_checklists((int)$org_id);

        if(!empty($list)){
            return response()->json(['success' => true, 'checkilists' => $list]);
        } else return response()->json(['success' => false]);
    }

    public function upsert_checklist(Request $request){
        $id = $request->input('id');
        $title = $request->input('title');
        $part_value = $request->input('part_value');
        $station_value = $request->input('station_value');
        $org_id = $request->input('org_id');
        $form_json = $request->input('form_json');

        if(empty($name)) return response()->json(['success' => true]);

        if(empty($id))  ChecklistService::insert_checklist(['title' => $title, 'part_id' => $part_value, 'org_id' => $org_id, 'station_id' => $station_value, 'form_json' => $form_json]);
        else ChecklistService::update_checklist(['title' => $title, 'part_id' => $part_value, 'org_id' => $org_id, 'station_id' => $station_value, 'form_json' => $form_json], ['id' => $id]);
        
        $list = ChecklistService::get_checklists((int)$org_id);
        if(!empty($list)){
            return response()->json(['success' => true, 'checkilists' => $list]);
        } else return response()->json(['success' => true]);
    }

    public static function delete_checklist(Request $request){
        $id = $request->input('id');
        $org_id = $request->input('org_id');

        if(empty($id)) return response()->json(['success' => true]);
        ChecklistService::delete_checklist_by_id(['id' => $id]);

        $list = ChecklistService::get_checklists((int)$org_id);
        if(!empty($list)){
            return response()->json(['success' => true, 'checkilists' => $list]);
        } else return response()->json(['success' => true]);
    }
}
