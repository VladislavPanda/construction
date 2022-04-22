<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Note;

class NoteController extends Controller
{
    public function store($notes){
        Note::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
            ],
            [
                'notes' => json_encode($notes, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
            ]
        );
    }

    public function getItems(){
        $items= [];

        $items = Note::where('user_id', Auth::user()->id)->get()->toArray();
        
        if(isset($items[0]) && $items[0]['notes'] != '[]'){
            $items = json_decode($items[0]['notes'], true);
            $items = $items['notes'];
        }

        return $items;
    } 
}
