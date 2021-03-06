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
        'noTasks' => 'Без задач',
        'set' => 'В работе'
    ];

    // Записать задачу в БД
    public function store($driverTask){
        //$currentTasksCnt = 1;
        $sSort = $driverTask['start_date'];
        $sSort = str_replace('-', '/', $sSort);
        $sSort = explode('/', $sSort);
        $sSortNew = $sSort[2] . '/' . $sSort[1] . '/' . $sSort[0];
        $driverTask['start_date'] = $sSortNew;

        $eSort = $driverTask['end_date'];
        $eSort = str_replace('-', '/', $eSort);
        $eSort = explode('/', $eSort);
        $eSortNew = $eSort[2] . '/' . $eSort[1] . '/' . $eSort[0];
        $driverTask['end_date'] = $eSortNew;

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
        $updatedTask['reject_reason'] = null;
        $updatedTask['status'] = self::$statuses['set'];
        
        $sSort = $updatedTask['start_date'];
        $sSort = str_replace('-', '/', $sSort);
        $sSort = explode('/', $sSort);

        if(strlen($sSort[0]) != 4){
            $sSortNew = $sSort[2] . '/' . $sSort[1] . '/' . $sSort[0];
            $updatedTask['start_date'] = $sSortNew;
        }else{
            $sSortNew = $sSort[0] . '/' . $sSort[1] . '/' . $sSort[2];
            $updatedTask['start_date'] = $sSortNew;
        }
        
        $eSort = $updatedTask['end_date'];
        $eSort = str_replace('-', '/', $eSort);
        $eSort = explode('/', $eSort);

        if(strlen($eSort[0]) != 4){
            $eSortNew = $eSort[2] . '/' . $eSort[1] . '/' . $eSort[0];
            $updatedTask['end_date'] = $eSortNew;
        }else{
            $eSortNew = $eSort[0] . '/' . $eSort[1] . '/' . $eSort[2];
            $updatedTask['end_date'] = $eSortNew;
        }

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

    // Проверяем, есть ли задачи у водителя
    public function taskValidate($userId){
        //$flag = false;
        $statusTitle = Status::select('title')->where('user_id', $userId)->get()->toArray();
        if(!empty($statusTitle)){
            if($statusTitle[0]['title'] == self::$statuses['hasTasks']) return true; 
        }
        
        return false;        
    }
}
