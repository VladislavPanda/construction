<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Status;

class TaskController extends Controller
{
    private static $statuses = [
        'hasTasks' => 'Задачи назначены',
        'done' => 'Выполнено',
        'rejected' => 'Не выполнено'
    ];

    public function store($driverTask){
        $userId = $driverTask['driver_id']; 
        Task::create($driverTask); // Записываем объект задачи

        // Проверка и запись статуса
        $status = Status::where('user_id', $userId)->get()->toArray();
        if(empty($status)) Status::create(['user_id' => $userId, 'title' => self::$statuses['hasTasks']]);
        else{ 
            $status = Status::where('user_id', $userId);
            $status->update(['title' => self::$statuses['hasTasks']]);
        }
        
        // Нужны проверки
        return true;
    }

    public function setDone($taskId){
        $flag = false;
        $task = Task::find($taskId);

        $task->update(['status' => self::$statuses['done']]);

        return true;
    }

    public function setReject($taskId, $rejectReason){
        $flag = false;
        $task = Task::find($taskId);

        $task->update(['status' => self::$statuses['rejected'], 'reject_reason' => $rejectReason]);

        return true;
    }
}
