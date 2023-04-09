<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Timeline;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    public function index()
    {
       $timelines = DB::table('timelines')->get();
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
