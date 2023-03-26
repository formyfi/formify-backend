<?php namespace App\Services;

use App\Http\Models\{
  Station,
};

use Illuminate\Support\Facades\{
  Cache
};


class StationServices { 

    public static function insert_station(Array $station_details){
        
       $user_id =  Station::upsert_station($station_details);

       return $user_id;
    }

    public static function update_station(Array $station_details, Array $where){
        
        Station::upsert_station($station_details, $where);
 
        return true;
    }

    public static function get_stations_list(Int $org_id){

        return Station::get_stations_list($org_id);

    }

    public static function delete_station_by_id(Array $where){

       return Station::delete_station($where);
    }
}