<?php

namespace App\Services;

use App\Models\Job;
use App\Models\Task;

class DiagramsService{

    public function jobsChart(){
        $set = 0;
        $done = 0;
        $rejected = 0;
        $cancelled = 0;

        $set = Job::where('status', 'В работе')->count();
        $done = Job::where('status', 'Выполнено')->count();
        $rejected = Job::where('status', 'Не выполнено')->count();
        $cancelled = Job::where('status', 'Отменено')->count();

        return [$set, $done, $rejected, $cancelled];
    }
}