<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function store($data){
        Task::create($data);
    }

    public function delete($id){
        $task = Task::find($id);

        $task->delete();
    }
}
