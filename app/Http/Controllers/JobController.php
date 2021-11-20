<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Job;
use App\Models\User;

class JobController extends Controller
{
    public function store($job){
        $flag = false;
        $jobTitle = explode('-', $job['worker']);
        $jobTitle = trim($jobTitle[1]);
        $job['job'] = $jobTitle;

        $surname = explode(' ', $job['worker']);
        $surname = $surname[0];

        $worker = User::select('id')->where('surname', $surname)->get()->toArray();
        $job['worker_id'] = $worker[0]['id'];
        unset($job['worker']);

        Job::create($job);

        return true;
    }

    public function getJobs($projectId){
        $jobs = Job::where('project_id', $projectId)->get()->toArray();

        return $jobs;
    }
}
