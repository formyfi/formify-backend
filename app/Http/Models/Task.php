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

    public static function upsert_part(Array $update_params, Array $where_params=[]){

        if(empty($where_params)){

            $insert_id = DB::table('parts')
            ->insertGetId($update_params);

            return ($insert_id) ? $insert_id : false;
        } else {

            DB::table('parts')
            ->where($where_params)
            ->update($update_params);

            return true;
        }

    }

    public static function upsert_part_vnumber(Array $update_params){

        
            $exist = DB::select("SELECT id FROM part_vnumber WHERE v_num = ? AND part_id = ?", [$update_params['v_num'], $update_params['part_id']]);
           
           if(empty($exist)){
            $insert_id = DB::table('part_vnumber')
            ->insertGetId($update_params);
           }
           
            return true;
    }

    public static function delete_part(Array $where){
    
        DB::table('parts')
            ->where($where)
            ->delete();

            return true;
    }

}