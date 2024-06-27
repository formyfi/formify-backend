<?php

namespace App\Http\Controllers;
use App\Services\ChecklistServices;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\{
    Checklist,
};

class ChecklistController extends Controller
{
    public function get_checklists(Request $request){
        $org_id = $request->input('org_id');
        $slug = $request->input('slug');
        $id = $request->input('id');
        
        if($slug === 'form_only'){

            if(empty($id)) return response()->json(['success' => false]);
        
            $form = ChecklistServices::get_checklist_form($id);

            if(!empty($form)){
                return response()->json(['success' => true, 'data' => $form->form_json]);
            } else return response()->json(['success' => false]);

        } else {

            if(empty($org_id)) return response()->json(['success' => false]);
            
            $list = ChecklistServices::get_checklists_by_org_id((int)$org_id);

            if(!empty($list)){
                return response()->json(['success' => true, 'checklists' => $list]);
            } else return response()->json(['success' => false]);
        }

        
    }

    public function upsert_checklist(Request $request){
        $id = $request->input('id');
        $name = $request->input('name');
        $title = $request->input('title');
        $part_value = $request->input('part');
        $station_value = $request->input('station');
        $org_id = $request->input('org_id');
        $unique_id = $request->input('unique_id');

        if(empty($name)) return response()->json(['success' => false]);

        $exist = ChecklistServices::get_checklists_by_id($id);
            if(empty($exist)){
                $duplicate_name = Checklist::get_checklists_by_name_and_org_id($name, $org_id);
                $unique_exist =  Checklist::check_if_station_part_allocated($unique_id, $org_id);
                if(empty($duplicate_name) && empty($unique_exist)){
                    ChecklistServices::insert_checklist(['name'=> $name, 'title' => $title, 'unique_id' => $unique_id,'part_id' => $part_value, 'org_id' => $org_id, 'station_id' => $station_value]); 
                } 
                else return response()->json(['success' => false, 'message' => !empty($duplicate_name) ? "Duplicate form name." : "Only one form can be created per part per operation."]);
            
            } else ChecklistServices::update_checklist(['name'=> $name, 'title' => $title, 'unique_id' => $unique_id,'part_id' => $part_value, 'org_id' => $org_id, 'station_id' => $station_value], ['id' => $id]);
            
            $list = ChecklistServices::get_checklists_by_org_id((int)$org_id);
            if(!empty($list)){
                return response()->json(['success' => true, 'checkilists' => $list]);
            } else return response()->json(['success' => true]);
       
    }

    public function upsert_checklist_form(Request $request){
        $id = $request->input('id');
        $org_id = $request->input('org_id');
        $form_json = $request->input('form_json');
        $is_draft = $request->input('is_draft');

        if(empty($id)) return response()->json(['success' => false]); 
        else ChecklistServices::update_checklist(['form_json' => $form_json, 'is_draft' => $is_draft], ['id' => $id]);
        
        $list = ChecklistServices::get_checklists_by_org_id((int)$org_id);
        if(!empty($list)){
            return response()->json(['success' => true, 'checkilists' => $list]);
        } else return response()->json(['success' => true]);
    }

    public function upsert_checklist_form_template(Request $request){
        $id = $request->input('id');
        $org_id = $request->input('org_id');
        $form_json = $request->input('form_json');
        $form_name = $request->input('form_name');

        if(empty($org_id) || empty($form_name)) return response()->json(['success' => false]); 

        if(!empty($id)) Checklist::update_template(['form_json' => $form_json], ['id' => $id]);
        else Checklist::insert_template(['name' => $form_name, 'org_id' => $org_id, 'form_json' => json_encode($form_json)]);

        $list = Checklist::get_templates((int)$org_id);

        if(!empty($list)){
            return response()->json(['success' => true, 'templates' => $list]);
        } else return response()->json(['success' => true]);
    }

    public function get_templates(Request $request){
        $org_id = $request->input('org_id');
       
        if(empty($org_id)) return response()->json(['success' => false]); 
        $list = Checklist::get_templates((int)$org_id);

        if(!empty($list)){
            return response()->json(['success' => true, 'templates' => $list]);
        } else return response()->json(['success' => true]);
    }

    public static function delete_checklist(Request $request){
        $id = $request->input('id');
        $org_id = $request->input('org_id');

        if(empty($id)) return response()->json(['success' => true]);
        ChecklistServices::delete_checklist_by_id(['id' => $id]);

        $list = ChecklistServices::get_checklists_by_org_id((int)$org_id);
        if(!empty($list)){
            return response()->json(['success' => true, 'checkilists' => $list]);
        } else return response()->json(['success' => true]);
    }
}
