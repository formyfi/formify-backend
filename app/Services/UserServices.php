<?php namespace App\Services;

use App\Http\Models\{
  Users,
};

use Illuminate\Support\Facades\{
  Cache
};


class UserServices { 

  
    public static function insert_user(Array $user_details){
        
       $user_id =  Users::upsert_user($user_details);

       return $user_id;
    }

    public static function update_user_details_by_id(Array $user_details){
        
        Users::upsert_user($user_details, ['id' => $user_details['id']]);
 
        return true;
    }

    public static function get_users_list(Int $org_id){

        return Users::get_users_list($org_id);

    }
}