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
       $station_ids= null;
       if(!empty($user_details['station_value'])) $station_ids = $user_details['station_value'];
       unset($user_details['user_type_value']); 
       unset($user_details['station_value']); 
       unset($user_details['station']);    

       $user_id =  Users::upsert_user($user_details);
       if(!empty($user_id) && !empty($station_ids)){
        Users::delete_user_station(['user_id' => $user_id]);
        foreach($station_ids AS $station_value){
          $station_ids[] =  $station_value['id'];
          Users::associate_user_with_station(['user_id' => $user_id, 'station_id' => $station_value['id']]);
        }
      }
      return $user_id;
    }

    public static function update_user_details_by_id(Array $user_details){
        
      if(empty($user_details['user_type']) && !empty($user_details['user_type_value'])) $user_details['user_type'] = $user_details['user_type_value']['value'];
      $station_ids= null;
      if(!empty($user_details['station_value'])) $station_ids = $user_details['station_value'];
      unset($user_details['user_type_value']); 
      unset($user_details['station_value']); 
      unset($user_details['station']); 
      $user_id = $user_details['id'];

      Users::upsert_user($user_details, ['id' => $user_id]);
      
      if(!empty($user_id) && !empty($station_ids)){
        Users::delete_user_station(['user_id' => $user_id]);
        foreach($station_ids AS $s){
          Users::associate_user_with_station(['user_id' => $user_id, 'station_id' => $s['id']]);
        }
      }
      return true;
    }

    public static function delete_user(Array $where){

      return Users::delete_user($where);
   }

    public static function get_users_list(Int $org_id){

        return Users::get_users_list($org_id);

    }
}