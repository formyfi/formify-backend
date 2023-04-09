<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Timeline;
use Illuminate\Http\Request;
use DB;

class TimelineController extends Controller
{
    public function index(Request $request)
    {
        $vnumberId = $request->form_vnumber_id;
       $timelines = DB::table('timelines')->where(['form_vnumber_id'=> $vnumberId])->get();
        return response()->json(['data' => $timelines]);

    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required',
            'form_vnumber_id' => 'required',
            'type' => 'required',
            'text' => 'required',
            'images' => 'nullable',
        ]);

        $data['created_at'] = now();
        $timelineId = DB::table('timelines')->insertGetId($data);
        $timeline = DB::table('timelines')->find($timelineId);

        return response()->json(['data' => $timeline], 201);

    }
}
