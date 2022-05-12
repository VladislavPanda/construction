<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Job;
use App\Models\User;
use App\Models\BudgetBid;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    private static $statuses = [
        'set' => 'В работе',
        'done' => 'Выполнено',
        'rejected' => 'Не выполнено',
        'cancelled' => 'Отменено'
    ];

    public function store($job){
        $flag = false;
        $jobTitle = explode('-', $job['worker']);
        $jobTitle = trim($jobTitle[1]);
        $job['job'] = $jobTitle;

        $surname = explode(' ', $job['worker']);
        $surname = $surname[0];

        $eSort = $job['date'];
        $eSort = str_replace('-', '/', $eSort);
        $eSort = explode('/', $eSort);
        $eSortNew = $eSort[2] . '/' . $eSort[1] . '/' . $eSort[0];
        $job['date'] = $eSortNew;

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

        $eSort = $updatedJob['date'];
        $eSort = str_replace('-', '/', $eSort);
        $eSort = explode('/', $eSort);
        $eSortNew = $eSort[2] . '/' . $eSort[1] . '/' . $eSort[0];
        $updatedJob['date'] = $eSortNew;

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

    public function delete($jobId){
        $flag = false;

        $job = Job::find($jobId);
        $job->update(['status' => self::$statuses['cancelled']]);
        $job->delete();

        return true;
    }

    public function putBudgetBid($item){
        $item['status'] = 'На рассмотрении';
        $currentBudget = Project::select('budget')->where('id', $item['id'])->get();
        if($currentBudget[0]->budget < $item['sum']) return false;
        else{
            BudgetBid::create([
                'description' => $item['budget_bid_description'],
                'sum' => $item['sum'],
                'project_id' => $item['id'],
                'status' => $item['status'],
                'user_id' => Auth::user()->id
            ]);
        }

        return 'Заявка передана на рассмотрение';
    } 
}
