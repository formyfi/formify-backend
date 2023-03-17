<?php namespace App\Services;

use App\Http\Models\{
  Users,
};

use Illuminate\Support\Facades\{
  Cache
};


class UserServices { 

    public static function insert_user(Array $user_details){
      
       if(empty($user_details['user_type']) && !empty($user_details['user_type_value'])) $user_details['user_type'] = $user_details['user_type_value']['value'];
       
       $station_value = null;
       if(!empty($user_details['station_value'])) $station_value = $user_details['station_value']['value'];
       unset($user_details['user_type_value']); 
       unset($user_details['station_value']); 
       unset($user_details['station']);    

       $user_id =  Users::upsert_user($user_details);
      if(!empty($user_id) && !empty($station_value)) Users::associate_user_with_station(['user_id' => $user_id, 'station_id' => $station_value]);

      return $user_id;
    }

    public static function update_user_details_by_id(Array $user_details){
        
      if(empty($user_details['user_type']) && !empty($user_details['user_type_value'])) $user_details['user_type'] = $user_details['user_type_value']['value'];
       
      $station_value = null;
      if(!empty($user_details['station_value'])) $station_value = $user_details['station_value']['value'];
      unset($user_details['user_type_value']); 
      unset($user_details['station_value']); 
      unset($user_details['station']); 

      Users::upsert_user($user_details, ['id' => $user_details['id']]);
      
      if(!empty($station_value)) Users::associate_user_with_station(['user_id' => $user_details['id'], 'station_id' => $station_value]);
      return true;
    }

    public static function delete_user(Array $where){

      return Users::delete_user($where);
   }

    public static function get_users_list(Int $org_id){

        return Users::get_users_list($org_id);

    }
}