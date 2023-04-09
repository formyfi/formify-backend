<?php

namespace App\Http\Controllers;
use App\Services\PartService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\{
    Part,
  };

class PartController extends Controller
{
    public function get_part_list(Request $request){
        $org_id = $request->input('org_id');

        if(empty($org_id)) return response()->json(['success' => false]);
        
        $list = PartService::get_part_list((int)$org_id);

        if(!empty($list)){
            return response()->json(['success' => true, 'part_list' => $list]);
        } else return response()->json(['success' => false]);
    }

    public function get_parts_by_station(Request $request){
        $station_id = $request->input('station_id');

        if(empty($station_id)) return response()->json(['success' => false]);
        
        $list = Part::get_parts_by_station((int)$station_id);

        if(!empty($list)){
            return response()->json(['success' => true, 'part_list' => $list]);
        } else return response()->json(['success' => false]);
    }

    public function get_part_vnumbers(Request $request){
        $part_id = $request->input('part_id');
        $station_id = $request->input('station_id');
        $org_id = $request->input('org_id');

        if(empty($part_id)) return response()->json(['success' => false]);
        
        $list = Part::get_part_vnumbers((int)$part_id, (int)$station_id, (int)$org_id);

        if(!empty($list)){
            return response()->json(['success' => true, 'v_numbers' => $list]);
        } else return response()->json(['success' => false]);
    }

    public function upsert_part(Request $request){
        $id = $request->input('id');
        $name = $request->input('name');
        $description = $request->input('description');
        $station_value = $request->input('station_value');
        $v_numbers = $request->input('v_numbers');
        $org_id = $request->input('org_id');

        if(empty($name)) return response()->json(['success' => true]);

        if(empty($id))  PartService::upsert_part(['name' => $name, 'description' => $description, 'org_id' => $org_id, 'station_id' => $station_value, 'v_numbers' => $v_numbers]);
        else PartService::upsert_part(['name' => $name, 'description' => $description, 'station_id' => $station_value, 'v_numbers' => $v_numbers], ['id' => $id]);
        
        $list = PartService::get_part_list((int)$org_id);
        if(!empty($list)){
            return response()->json(['success' => true, 'part_list' => $list]);
        } else return response()->json(['success' => true]);
    }

    public static function delete_part (Request $request){
        $id = $request->input('id');
        $org_id = $request->input('org_id');

        if(empty($id)) return response()->json(['success' => true]);
        PartService::delete_part_by_id(['id' => $id]);

        $list = PartService::get_part_list((int)$org_id);
        if(!empty($list)){
            return response()->json(['success' => true, 'part_list' => $list]);
        } else return response()->json(['success' => true]);
    }
}
