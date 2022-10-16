<?php

namespace App\Http\Controllers;

use App\Entry;
use App\Project;
use Illuminate\Support\Carbon;
use \Illuminate\Support\Facades\Request;

class EntryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($project_id)
    {
        return response()->json(['entries' => Entry::where('project_id', $project_id)->get()]);
    }

    public function upsert()
    {
        Entry::updateOrCreate(
            ['start' => Carbon::createFromTimestamp(floor(Request::post('start')/1000)), 'project_id' => Request::post('project_id')],
            ['end' => Carbon::createFromTimestamp(floor(Request::post('end')/1000))]
        );
    }

    public function projectTotalTime($project_id)
    {
        return response()->json([
            'totalProjectTime' => Project::find($project_id)->totalProjectTime
        ]);
    }

}
