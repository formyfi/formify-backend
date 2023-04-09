<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Timeline;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    public function index()
    {
        $timelines = Timeline::all();
        return response()->json(['data' => $timelines]);
    }

    public function show(Timeline $timeline)
    {
        return response()->json(['data' => $timeline]);
    }

    public function store(Request $request)
    {
        $timeline = Timeline::create($request->all());
        return response()->json(['data' => $timeline], 201);
    }

    public function update(Request $request, Timeline $timeline)
    {
        $timeline->update($request->all());
        return response()->json(['data' => $timeline]);
    }

    public function destroy(Timeline $timeline)
    {
        $timeline->delete();
        return response()->json(['message' => 'Timeline deleted']);
    }
}
