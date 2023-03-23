<?php

namespace App\Http\Controllers;
use App\Services\ChecklistServices;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChecklistController extends Controller
{
    public function get_checklists(Request $request){
        $org_id = $request->input('org_id');

        if(empty($org_id)) return response()->json(['success' => false]);
        
        $list = ChecklistServices::get_checklists((int)$org_id);

        if(!empty($list)){
            return response()->json(['success' => true, 'checkilists' => $list]);
        } else return response()->json(['success' => false]);
    }

    public function upsert_checklist(Request $request){
        $id = $request->input('id');
        $title = $request->input('title');
        $part_value = $request->input('part');
        $station_value = $request->input('station');
        $org_id = $request->input('org_id');
        $form_json = $request->input('form_json');

        if(empty($id))  ChecklistServices::insert_checklist(['title' => $title, 'part_id' => $part_value, 'org_id' => $org_id, 'station_id' => $station_value, 'form_json' => $form_json]);
        else ChecklistServices::update_checklist(['title' => $title, 'part_id' => $part_value, 'org_id' => $org_id, 'station_id' => $station_value, 'form_json' => $form_json], ['id' => $id]);
        
        $list = ChecklistServices::get_checklists((int)$org_id);
        if(!empty($list)){
            return response()->json(['success' => true, 'checkilists' => $list]);
        } else return response()->json(['success' => true]);
    }

    public static function delete_checklist(Request $request){
        $id = $request->input('id');
        $org_id = $request->input('org_id');

        if(empty($id)) return response()->json(['success' => true]);
        ChecklistServices::delete_checklist_by_id(['id' => $id]);

        $list = ChecklistServices::get_checklists((int)$org_id);
        if(!empty($list)){
            return response()->json(['success' => true, 'checkilists' => $list]);
        } else return response()->json(['success' => true]);
    }
}
