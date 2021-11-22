<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Job;
use App\Models\User;

class JobController extends Controller
{
    private static $statuses = [
        'set' => 'В работе',
        'done' => 'Выполнено',
        'rejected' => 'Не выполнено'
    ];

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

    public function getUpdatedJob($jobId){
        $job = Job::find($jobId);

        return $job;
    }

    public function update($updatedJob){
        $flag = false;
        $jobTitle = explode('-', $updatedJob['worker']);
        $jobTitle = trim($jobTitle[1]);
        $updatedJob['job'] = $jobTitle;

        $surname = explode(' ', $updatedJob['worker']);
        $surname = $surname[0];

        $worker = User::select('id')->where('surname', $surname)->get()->toArray();
        $updatedJob['worker_id'] = $worker[0]['id'];
        unset($updatedJob['worker']);

        $updatedJob['status'] = self::$statuses['set'];
        $updatedJob['reject_reason'] = null;
        $job = Job::find($updatedJob['id']);
        $job->update($updatedJob);

        return true;
    }

    public function getJobs($projectId){
        $jobs = Job::where('project_id', $projectId)->get()->toArray();

        return $jobs;
    }

    public function setDone($jobId){
        $flag = false;

        $job = Job::find($jobId);
        $job->update(['status' => self::$statuses['done']]);
        
        return true;
    }

    public function setReject($jobId, $rejectReason){
        $flag = false;

        $job = Job::find($jobId);
        $job->update(['status' => self::$statuses['rejected'], 'reject_reason' => $rejectReason]);
        
        return true;
    }
}
