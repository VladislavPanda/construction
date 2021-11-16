<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;

class TaskController extends Controller
{
    public function store($driverTask){
        $userId = $driverTask['driver_id']; 
        Task::create($driverTask); // Записываем объект задачи

        $user = User::find($userId);
        $user->update(['status' => 'Задачи назначены']);

        // Нужны проверки
        return true;
    }
}
