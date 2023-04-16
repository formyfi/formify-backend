<?php namespace App\Http\Models;

use App\Http\Models\Model;
use DB;



class Users extends Model { 

    //Get Queries

    //Set Queries

    public static function upsert_user(Array $update_params, Array $where_params=[]){

        if(empty($where_params)){

            $insert_id = DB::table('users')
            ->insertGetId($update_params);

            return ($insert_id) ? $insert_id : false;
        } else {

            DB::table('users')
            ->where($where_params)
            ->update($update_params);

            return true;
        }

    }

    public static function associate_user_with_station(Array $update_params){

                DB::table('user_station')
                 ->insertGetId($update_params);
                return true;

    }

    public static function associate_user_with_area(Array $update_params){

        DB::table('users_area')
         ->insertGetId($update_params);
        return true;

    }

    
    public static function delete_user(Array $where){
    
        DB::table('users')
            ->where($where)
            ->delete();

            return true;
    }

    public static function delete_user_station(Array $where){
    
        DB::table('user_station')
        ->where($where)
        ->delete();

            return true;
    }

    public static function delete_user_area(Array $where){
    
        DB::table('users_area')
        ->where($where)
        ->delete();

            return true;
    }

    
    public static function get_users_list(Int $org_id){
       
            $results = DB::select("SELECT u.*, GROUP_CONCAT(DISTINCT us.station_id) AS station_id, GROUP_CONCAT(DISTINCT s.name SEPARATOR ', ') AS station_names, GROUP_CONCAT(DISTINCT ua.area_id) AS user_areas, GROUP_CONCAT(DISTINCT a.name SEPARATOR ', ') AS user_areas_names
                FROM users u
                LEFT JOIN user_station us ON (u.id = us.user_id)
                LEFT JOIN stations s ON (s.id = us.station_id)
                LEFT JOIN users_area ua ON (ua.user_id = u.id)
                LEFT JOIN functional_areas a ON (a.id = ua.area_id)
                WHERE u.org_id = ? AND u.active = 1 AND u.super_user != 1 GROUP BY u.id", [$org_id]);
            
            return (count($results) > 0) ? $results : false;
    }

    public static function get_stations_by_user_id(Int $id){
       
        $results = DB::select("SELECT s.id AS station_id, s.name, s.id AS value
            FROM user_station us
            JOIN stations s ON (s.id = us.station_id)
            WHERE us.user_id = ?", [$id]);
        
        return (count($results) > 0) ? $results : false;
    }

    public static function get_areas_by_user_id(Int $id){
       
        $results = DB::select("SELECT GROUP_CONCAT(DISTINCT us.area_id) AS areas
            FROM users_area us
            WHERE us.user_id = ? GROUP BY us.user_id", [$id]);
        
        return (count($results) > 0) ? $results[0] : false;
    }

    public static function get_org_details(Int $org_id){
       
        $results = DB::select("SELECT *
            FROM organization
            WHERE id=?", [$org_id]);
        
        return (count($results) > 0) ? $results[0] : [];
    }
}