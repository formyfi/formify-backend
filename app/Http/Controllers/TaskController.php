<?php

namespace App\Http\Controllers;
use App\Services\TaskService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\{
    Task,Checklist
  };

class TaskController extends Controller
{
    public function get_tasklists(Request $request){
        $org_id = $request->input('org_id');
        $user_id = $request->input('user_id');
        $perPage = $request->input('perPage');
        $page = $request->input('page');
        $searchText = $request->input('searchText');
    
        if(empty($org_id) || empty($user_id)) return response()->json(['success' => false]);
    
        $list = (array) Task::get_task_list((int)$org_id, (int)$user_id, $searchText , (int)$perPage, (int)$page);
    
        $list = array_values($list);
        if(!empty($list)){
            foreach($list AS $key => $item){
                $list[$key]->form_json = json_decode($item->form_json, true);
                $list[$key]->form_data = json_decode($item->form_data, true);
                $name_keys = [];
                foreach($list[$key]->form_json AS $index => $field){
                    if($field['type'] !== 'header'){
                        foreach($list[$key]->form_data AS $k=>$fd){
                            if($field['name'] === $k){
                                $list[$key]->form_json[$index]['field_value'] = $fd;
                            }
                        }
                    }
                }
            }
            return response()->json(['success' => true, 'task_lists' => $list, 'total_records' => Task::get_total_task_list((int)$org_id, (int)$user_id)]);
        } else {
            return response()->json(['success' => false]);
        }
    }    

    public function get_full_tasklist_data(Request $request){
        $org_id = $request->input('org_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        if(empty($org_id)) return response()->json(['success' => false]);
        
        $list = Task::get_full_tasklist_data((int)$org_id, $start_date, $end_date);
        
        if(!empty($list)){
            foreach ($list as $row) {
                $dates[] = $row->date;
                $totalRecords[] = $row->total_records;
                $compliantRecords[] = $row->compliant_records;
                $nonCompliantRecords[] = $row->non_compliant_records;
            }
            return response()->json(['success' => true, 'full_lists' => $list, 'dates' => $dates, 'totalRecords' => $totalRecords, 'compliantRecords' => $compliantRecords, 'nonCompliantRecords' => $nonCompliantRecords]);
        } else return response()->json(['success' => false]);
    }
    
    public function get_station_tasklist_data(Request $request){
        $org_id = $request->input('org_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        if(empty($org_id)) return response()->json(['success' => false]);
        
        $list = Task::get_station_tasklist_data((int)$org_id, $start_date, $end_date);
        
        if(!empty($list)){
            foreach ($list as $row) {
                $dates[] = $row->date;
                $totalRecords[] = $row->total_records;
                $compliantRecords[] = $row->compliant_records;
                $nonCompliantRecords[] = $row->non_compliant_records;
            }
            return response()->json(['success' => true, 'full_lists' => $list, 'dates' => $dates, 'totalRecords' => $totalRecords, 'compliantRecords' => $compliantRecords, 'nonCompliantRecords' => $nonCompliantRecords]);
        } else return response()->json(['success' => false]);
    }

    public function get_total_stations_inspections(Request $request){
        $org_id = $request->input('org_id');
    
        if(empty($org_id)) return response()->json(['success' => false]);
        
        $list = Task::get_total_stations_inspections((int)$org_id);
        
        if(!empty($list)){
            return response()->json(['success' => true, 'full_lists' => $list]);
        } else return response()->json(['success' => false]);
    }

    public static function get_task_form(Request $request){
        $station_value = $request->input('station_value');
        $part_value = $request->input('part_value');
        $v_number = $request->input('v_number');
        
        if(empty($station_value) || empty($part_value)) return response()->json(['success' => false]);

        $form_data = TaskService::get_task_form((int)$station_value, (int)$part_value, $v_number);

        if(!empty($form_data)){
            return response()->json(['success' => true, 'form_data' => $form_data]);
        } else return response()->json(['success' => false]);

    }
    
    public static function update_task_form(Request $request){
        $id = $request->input('id');
        $form_id = $request->input('form_id');
        $part_id = $request->input('part_id');
        $part_vnumber = $request->input('part_vnum');
        $station_id = $request->input('station_id');
        $org_id = $request->input('org_id');
        $form_json = $request->input('form_json');
        $user_id = $request->input('user_id');

        if(empty($form_id) || empty($part_vnumber) || empty($form_json)) return response()->json(['success' => false]);

        if(!empty($form_json)){
            $form_array = json_decode($form_json, true);
            $is_compliant = 1;
            if(!empty($form_array)){
                foreach($form_array AS $fs){
                    if(strtolower($fs) === 'fail' || strtolower($fs) === 'no' || strtolower($fs) === 'reject'){
                        $is_compliant = 0;
                        break;
                    }
                }
                if($is_compliant === 1){
                    $data = Checklist::get_checklist_form($form_id);
                    if(!empty($data)){
                        $form = $data->form_json;
                        $form = json_decode($form, true);
                        if(!empty($form)){
                            $is_break = false;
                            foreach($form_array AS $key => $value){
                                if(stristr($key, 'number')){
                                    foreach($form AS $f){
                                        if(!empty($f['name']) && $f['name'] === $key){
                                            if((!empty($f['max']) && (int)$value > (int)$f['max']) || (!empty($f['min']) && (int)$value < (int)$f['min'])) {
                                                $is_compliant = 0;
                                                $is_break = true;
                                                break;
                                            }
                                        }
                                    }
                                }
                                if($is_break === true) break;
                            }
                        }
                    }  else {
                        //Here if it comes here means actualy form is deleted but values exist. Add code to remove values
                    }
                }
            } else $is_compliant = 0;
        }
        
        if(!empty($id)){
            Task::update_checklist_task_data(['form_data' => $form_json, 'last_updated_id' => $user_id], ['id' => $id]);
            return response()->json(['success' => true, "update" => true]);
        } else {
           $record_id = Task::insert_checklist_task_record(['form_id'=> $form_id, 'part_id' => $part_id, 'compliance_ind' => $is_compliant ,'vnum_id' => $part_vnumber,'station_id' => $station_id, 'org_id' => $org_id, 'last_updated_id' => $user_id]);
           
           if(!empty($record_id)){
            Task::insert_checklist_task_data(['checklist_vnum_record_id' => $record_id,'form_data' => $form_json, 'last_updated_id' => $user_id]);
           }
           return response()->json(['success' => true, "insert" => true]);
        } 
       
    }
}
