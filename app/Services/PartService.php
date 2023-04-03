<?php namespace App\Services;

use App\Http\Models\{
  Part,
};

use Illuminate\Support\Facades\{
  Cache
};


class PartService { 

    public static function upsert_part(Array $part_details, Array $where = []){
      
        $v_numbers = '';
        $station_ids= null;
        if(!empty($part_details['v_numbers'])) $v_numbers = $part_details['v_numbers'];
        if(!empty($part_details['station_id'])) $station_ids = $part_details['station_id'];
        unset($part_details['v_numbers']);
        unset($part_details['station_id']);
        $part_id = null;

        if(empty($where)){
          $part_id =  Part::upsert_part($part_details);
            if(!empty($part_id) && !empty($v_numbers)){
              $v_numbers = explode(',', $v_numbers);
                foreach ($v_numbers as $key => $v_number) {
                  Part::upsert_part_vnumber(['part_id' => $part_id, 'v_num' => $v_number]);
                }
            } 
        } else{
          Part::upsert_part($part_details, $where);
          if(!empty($where['id']) && !empty($v_numbers)){
            $v_numbers = explode(',', $v_numbers);
            foreach ($v_numbers as $key => $v_number) {
              Part::upsert_part_vnumber(['part_id' => $where['id'], 'v_num' => $v_number]);
            }
          } 
          $part_id = $where['id'];
        }
        

        if(!empty($part_id) && !empty($station_ids)){
          Part::delete_part_station(['part_id' => $part_id]);
          foreach($station_ids AS $station_value){
            $station_ids[] =  $station_value['id'];
            Part::upsert_part_station(['part_id' => $part_id, 'station_id' => $station_value['id']]);
          }
   
        }

        return $part_id;
    }

    public static function get_part_list(Int $org_id){

        return Part::get_part_list($org_id);

    }

    public static function delete_part_by_id(Array $where){

       return Part::delete_part($where);
    }
}