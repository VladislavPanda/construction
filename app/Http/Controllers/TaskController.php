<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Status;
use App\Services\AuthHandler;

class TaskController extends Controller
{
    private static $statuses = [
        'hasTasks' => 'Задачи назначены',
        'done' => 'Выполнено',
        'rejected' => 'Не выполнено',
        'noTasks' => 'Без задач'
    ];

    // Записать задачу в БД
    public function store($driverTask){
        //$currentTasksCnt = 1;
        $userId = $driverTask['driver_id']; 
        Task::create($driverTask); // Записываем объект задачи

        // Проверка и запись статуса
        $status = Status::where('user_id', $userId)->get()->toArray();
        if(empty($status)) Status::create(['user_id' => $userId, 'title' => self::$statuses['hasTasks']]);
        else{
            $currentTasksCnt = $status[0]['tasks_cnt'] + 1;
            $status = Status::where('user_id', $userId);
            $status->update(['title' => self::$statuses['hasTasks'], 'tasks_cnt' => $currentTasksCnt]);
        }
        
        // Нужны проверки
        return true;
    }

    // Отредактировать задачу
    public function update($updatedTask){
        $flag = false;
        $taskId = $updatedTask['id'];

        $task = Task::find($taskId);
        $task->update($updatedTask);

        return true;
    }

    // Установить задачу как выполненную
    public function setDone($taskId){
        $flag = false;
        // Находим и редактируем статус задачи
        $task = Task::find($taskId);

        $task->update(['status' => self::$statuses['done']]);

        // Редактируем число задач и проверяем статус
        $userId = AuthHandler::getCurrentUser();
        $status = Status::where('user_id', $userId)->get()->toArray();
        $status = $status[0];

        $currentTasksCnt = $status['tasks_cnt'] - 1;
        $status = Status::where('user_id', $userId);

        if($currentTasksCnt == 0) $status->update(['title' => self::$statuses['noTasks'], 'tasks_cnt' => $currentTasksCnt]);
        else $status->update(['tasks_cnt' => $currentTasksCnt]);

        return true;
    }

    // Устанвить задачу как невыполненную
    public function setReject($taskId, $rejectReason){
        $flag = false;
        $task = Task::find($taskId);

        $task->update(['status' => self::$statuses['rejected'], 'reject_reason' => $rejectReason]);

        return true;
    }

    // Получить текущую задачу на редактирование
    public function getCurrentTask($taskId){
        $task = Task::find($taskId);

        return $task;
    }
}
