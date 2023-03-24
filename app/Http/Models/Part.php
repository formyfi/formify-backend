<?php namespace App\Http\Models;

use App\Http\Models\Model;
use DB;



class Part extends Model { 

    //Get Queries

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

    public static function get_part_list(Int $org_id){
    
            $results = DB::select("SELECT s.*, s.id AS value, s.name AS label, GROUP_CONCAT(pv.v_num) AS v_numbers
                FROM parts s
                LEFT JOIN part_vnumber pv ON (pv.part_id = s.id) 
                WHERE s.org_id = ? GROUP BY s.id", [$org_id]);
            
            return (count($results) > 0) ? $results : false;
    }

    public static function delete_part(Array $where){
    
        DB::table('parts')
            ->where($where)
            ->delete();

            return true;
    }

}