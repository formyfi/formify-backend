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

    public static function get_users_list(Int $org_id){
       
            $results = DB::select("SELECT u.*
                FROM users u
                WHERE u.org_id = ? AND u.active = 1", [$org_id]);
            
            return (count($results) > 0) ? $results : false;
    }

}