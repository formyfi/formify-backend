<?php

namespace App\Http\Controllers;
use App\Services\StationServices;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StationController extends Controller
{
    public function get_station_list(Request $request){
        $org_id = $request->input('org_id');

        if(empty($org_id)) return response()->json(['success' => false]);
        
        $list = StationServices::get_stations_list((int)$org_id);

        if(!empty($list)){
            return response()->json(['success' => true, 'station_list' => $list]);
        } else return response()->json(['success' => false]);
    }

    public function upsert_station(Request $request){
        $id = $request->input('id');
        $name = $request->input('name');
        $type = $request->input('type');
        $org_id = $request->input('org_id');

        if(empty($name)) return response()->json(['success' => true]);
        
        if(empty($id))  StationServices::insert_station(['name' => $name, 'type' => $type, 'org_id' => $org_id]);
        else StationServices::update_station(['name' => $name, 'type' => $type], ['id' => $id]);
        
        $list = StationServices::get_stations_list((int)$org_id);
        if(!empty($list)){
            return response()->json(['success' => true, 'station_list' => $list]);
        } else return response()->json(['success' => true]);
    }

    public static function delete_station (Request $request){
        $id = $request->input('id');
        $org_id = $request->input('org_id');

        if(empty($id)) return response()->json(['success' => true]);
        StationServices::delete_station_by_id(['id' => $id]);

        $list = StationServices::get_stations_list((int)$org_id);
        if(!empty($list)){
            return response()->json(['success' => true, 'station_list' => $list]);
        } else return response()->json(['success' => true]);
    }
}
