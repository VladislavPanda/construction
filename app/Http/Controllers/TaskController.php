<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function store($driverTask){
        Task::create($driverTask);

        // Нужны проверки
        return true;
    }
}
