<?php namespace App\Http\Models;

use App\Http\Models\Model;
use DB;



class Station extends Model { 

    //Get Queries

    //Set Queries

    public static function upsert_station(Array $update_params, Array $where_params=[]){

        if(empty($where_params)){

            $insert_id = DB::table('stations')
            ->insertGetId($update_params);

            return ($insert_id) ? $insert_id : false;
        } else {

            DB::table('stations')
            ->where($where_params)
            ->update($update_params);

            return true;
        }

    }

    public static function get_stations_list(Int $org_id){
    
            $results = DB::select("SELECT s.*
                FROM stations s
                WHERE s.org_id = ? AND s.active = 1", [$org_id]);
            
            return (count($results) > 0) ? $results : false;
    }

    public static function delete_station(Array $where){
    
        DB::table('stations')
            ->where($where)
            ->delete();

            return true;
}

}