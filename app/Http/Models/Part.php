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

    public static function upsert_part_station(Array $update_params){

        $insert_id = DB::table('part_station')
        ->insertGetId($update_params);

        return ($insert_id) ? $insert_id : false;
          
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
    
            $results = DB::select("SELECT pt.*, pt.id AS value, pt.name AS label, GROUP_CONCAT(DISTINCT pv.v_num) AS v_numbers, GROUP_CONCAT(DISTINCT ps.station_id) AS station_id
                FROM parts pt
                LEFT JOIN part_vnumber pv ON (pv.part_id = pt.id)
                LEFT JOIN part_station ps ON (ps.part_id = pt.id)
                WHERE pt.org_id = ? GROUP BY pt.id", [$org_id]);
            
            return (count($results) > 0) ? $results : false;
    }

    public static function get_part_vnumbers(Int $part_id){
    
        $results = DB::select("SELECT pv.v_num
            FROM part_vnumber pv 
            WHERE pv.part_id = ?", [$part_id]);
        
        return (count($results) > 0) ? $results : false;
    }

    public static function get_parts_by_station(Int $station_id){
    
        $results = DB::select("SELECT p.*, p.id AS value, p.name AS label
            FROM parts p
            JOIN part_station ps ON (ps.part_id = p.id)
            WHERE ps.station_id = ?", [$station_id]);
        
        return (count($results) > 0) ? $results : false;
    }

    public static function delete_part(Array $where){
    
        DB::table('parts')
            ->where($where)
            ->delete();

            return true;
    }

    public static function delete_part_station(Array $where){
    
        DB::table('part_station')
        ->where($where)
        ->delete();

            return true;
    }

   
}