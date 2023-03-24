<?php namespace App\Http\Models;

use App\Http\Models\Model;
use DB;



class Checklist extends Model { 

    //Get Queries

    //Set Queries

    public static function upsert_checklist(Array $update_params, Array $where_params=[]){

        if(empty($where_params)){
            $insert_id = DB::table('forms')
            ->insertGetId($update_params);
            return ($insert_id) ? $insert_id : false;
        } else {

            DB::table('forms')
            ->where($where_params)
            ->update($update_params);

            return true;
        }

    }


    public static function get_checklists(Int $org_id){
    
            $results = DB::select("SELECT s.*, s.id, st.name as station_name, pt.name as part_name FROM forms s LEFT JOIN stations st ON st.id=s.station_id LEFT JOIN parts pt ON pt.id=s.part_id  WHERE s.org_id = ? GROUP BY s.id", [$org_id]);
            
            return (count($results) > 0) ? $results : false;
    }

    public static function delete_checklist(Array $where){
    
        DB::table('forms')
            ->where($where)
            ->delete();

            return true;
    }

}