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


            $exisiting_entry = DB::select("SELECT  us.id
                FROM user_station us
                WHERE us.user_id = ?", [$update_params['user_id']]);

            if(empty($exisiting_entry)){
                $insert_id = DB::table('user_station')
                 ->insertGetId($update_params);

                return ($insert_id) ? $insert_id : false;
            } else {
                DB::table('users')
                ->where('id','=',$exisiting_entry[0]['id'])
                ->update($update_params);

            return true;
            }

            

            return true;

    }

    
    public static function delete_user(Array $where){
    
        DB::table('users')
            ->where($where)
            ->delete();

            return true;
    }

    public static function get_users_list(Int $org_id){
       
            $results = DB::select("SELECT u.*, s.id AS station_id, s.name AS station_name
                FROM users u
                LEFT JOIN user_station us ON (u.id = us.user_id)
                LEFT JOIN stations s ON (s.id = us.station_id)
                WHERE u.org_id = ? AND u.active = 1", [$org_id]);
            
            return (count($results) > 0) ? $results : false;
    }

    public static function get_stations_by_user_id(Int $id){
       
        $results = DB::select("SELECT s.id AS station_id, s.name, s.id AS value
            FROM user_station us
            JOIN stations s ON (s.id = us.station_id)
            WHERE us.user_id = ?", [$id]);
        
        return (count($results) > 0) ? $results : false;
    }

    public static function get_org_details(Int $org_id){
       
        $results = DB::select("SELECT *
            FROM organization
            WHERE id=?", [$org_id]);
        
        return (count($results) > 0) ? $results[0] : [];
    }
}