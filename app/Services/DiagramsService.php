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

    public function jobs(){
        $architects = 0;
        $masons = 0;
        $fitters = 0;
        $plumbers = 0;
        $craneDrivers = 0;

        $architects = Job::where('job', 'Архитектор')->count();
        $masons = Job::where('job', 'Каменщик')->count();
        $fitters = Job::where('job', 'Монтажник')->count();
        $plumbers = Job::where('job', 'Сантехник')->count();
        $craneDrivers = Job::where('job', 'Водитель крана')->count();

        return [$architects, $masons, $fitters, $plumbers, $craneDrivers];
    }
}