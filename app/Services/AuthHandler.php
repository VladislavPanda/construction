<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class AuthHandler{

    public static function getCurrentUser(){
        $currentUserId = Auth::user()->id;

        return $currentUserId;
    }
}