<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Timeline;
use Illuminate\Http\Request;
use DB;
use Auth;

class TimelineController extends Controller
{
    public function get_vnum_timline(Request $request)
    {
        $org_id = $request->input('org_id');
        $v_num = $request->input('v_num');

       $timelines = DB::table('timelines')
                ->leftJoin('users', 'users.id', '=', 'timelines.user_id')
                ->leftJoin('stations', 'stations.id', '=', 'timelines.station_id')
                ->select('timelines.*', 'users.first_name', 'users.last_name', 'stations.name')
                ->where(['form_vnumber_id'=> $v_num])
                ->where(['timelines.org_id'=> $org_id])
                ->orderBy('timelines.id', 'desc')
                ->get();
        return response()->json(['data' => $timelines]);

    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'form_vnumber_id' => 'required',
            'type' => 'required',
            'text' => 'required',
            'images' => 'nullable',
            'station_id' => 'required',
            'org_id' => 'required',
        ]);

        $data['user_id'] = Auth::user()->id;
        $timelineId = DB::table('timelines')->insertGetId($data);
        $timeline = DB::table('timelines')->find($timelineId);

        return response()->json(['data' => $timeline], 201);

    }
}
