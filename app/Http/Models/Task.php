<?php namespace App\Http\Models;

use App\Http\Models\Model;
use DB;



class Task extends Model { 

    //Get Queries

    public static function get_task_entry(Int $station_value, Int $part_value, Int $v_number){

        $form_data = DB::select("SELECT cd.form_data FROM checklist_vnum_record cv JOIN checklist_data cd ON (cd.checklist_vnum_record_id = cv.id) WHERE cv.station_id = ? AND cv.part_id = ? AND cv.vnum_id=?", [$station_value, $part_value, $v_number]);

        return (count($form_data) > 0) ? $form_data[0]->form_data: false;
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
           } else return $exist->id;
           
            
    }

    public static function insert_checklist_task_data(Array $update_params){
    
         $insert_id = DB::table('checklist_data')
         ->insertGetId($update_params);
         
         return $insert_id;
      
    }

}