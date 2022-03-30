<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkerMessage;
use Illuminate\Support\Facades\Auth;

class WorkerMessageController extends Controller
{
    public function store($message){
        $message['user_id'] = Auth::user()->id;

        WorkerMessage::create($message);

        return true;
    }
}
