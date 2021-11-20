<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Job;

class JobController extends Controller
{
    public function store(){

    }

    public function getJobs($projectId){
        $jobs = Job::where('project_id', $projectId)->get()->toArray();

        return $jobs;
    }
}
