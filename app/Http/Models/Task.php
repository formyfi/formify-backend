<?php namespace App\Http\Models;

use App\Http\Models\Model;
use DB;



class Task extends Model { 

    //Get Queries

    public static function get_task_entry(Int $station_value, Int $part_value, Int $v_number){

        $form_data = DB::select("SELECT cd.form_data, cv.id FROM checklist_vnum_record cv JOIN checklist_data cd ON (cd.checklist_vnum_record_id = cv.id) WHERE cv.station_id = ? AND cv.part_id = ? AND cv.vnum_id=?", [$station_value, $part_value, $v_number]);

        return (count($form_data) > 0) ? $form_data[0]->form_data: false;
    }

    public static function get_task_list(Int $org_id, Int $user_id){

        $list = DB::select("SELECT CONCAT(u.first_name, ' ' ,u.last_name) AS last_updated_by_name, 
        cv.part_id, cv.station_id, cv.vnum_id, cv.form_id, 
        IF(cv.compliance_ind = 1, 'Yes', 'No') AS compliance_ind, 
        cd.form_data, fs.form_json, cd.last_updated_id, 
        s.name AS station_name, p.name AS part_name
        FROM checklist_vnum_record cv 
        LEFT JOIN checklist_data cd ON cd.checklist_vnum_record_id = cv.id
        LEFT JOIN stations s ON s.id = cv.station_id
        LEFT JOIN parts p ON p.id = cv.part_id
        LEFT JOIN forms fs ON fs.id = cv.form_id
        LEFT JOIN users u ON u.id = cd.last_updated_id
        WHERE cv.org_id = ? 
        AND EXISTS (SELECT 1 FROM user_station us WHERE us.station_id = cv.station_id AND us.user_id = ?)", [$org_id, $user_id]);

        return (count($list) > 0) ? $list : false;
    }

    
    //Set Queries

    public static function update_checklist_task_data(Array $update_params, Array $where_params){

        DB::table('checklist_data')
        ->where($where_params)
        ->update($update_params);

        return true;

    }

    public static function insert_checklist_task_record(Array $update_params){

        
            $exist = DB::select("SELECT id FROM checklist_vnum_record WHERE form_id = ? AND vnum_id = ?", [$update_params['form_id'], $update_params['vnum_id']]);
           
           if(empty($exist)){
            $insert_id = DB::table('checklist_vnum_record')
            ->insertGetId($update_params);
            return $insert_id;
           } else {
            $where_params = ['id' => $exist[0]->id];
            DB::table('checklist_vnum_record')
            ->where($where_params)
            ->update($update_params);
            return $exist[0]->id;
           } 
            
    }

    public static function insert_checklist_task_data(Array $update_params){

        $exist = DB::select("SELECT id FROM checklist_data WHERE checklist_vnum_record_id = ?", [$update_params['checklist_vnum_record_id']]);
        
        if(!empty($exist)){
            $where_params = ['checklist_vnum_record_id' => $update_params['checklist_vnum_record_id']];
            DB::table('checklist_data')
            ->where($where_params)
            ->update($update_params);

            return true;
        }else{
            $insert_id = DB::table('checklist_data')
            ->insertGetId($update_params);
            
            return $insert_id;
        }
        
      
    }

}