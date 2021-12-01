<?php

namespace App\Services;

use App\Models\Job;
use App\Models\Task;

class MessagesService{

    public static function getMessagesNum(){
        $tasksNum = 0;
        $jobsNum = 0;

        $tasksNum = Job::where('status', 'Не выполнено')->count();
        $jobsNum = Task::where('status', 'Не выполнено')->count();

        $total = $tasksNum + $jobsNum;

        return $total;
    }
}