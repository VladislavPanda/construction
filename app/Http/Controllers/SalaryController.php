<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Db;
use App\Models\Speciality;

class SalaryController extends Controller
{
    public function getSpecialities(){
        $specialities = [];
        $rolesAssoc = Db::table('roles')->select('name')
                                        ->where('name', '!=', 'Сотрудник')
                                        ->where('name', '!=', 'Администратор')
                                        ->get()
                                        ->toArray();

        foreach($rolesAssoc as $key => $value){
            $specialities[] = [
                'Специальность' => $value->name
            ]; 
        }

        $specialitiesAssoc = Speciality::select('title')->get()->toArray();
        
        foreach($specialitiesAssoc as $key => $value){
            $specialities[] = [
                                'Специальность' => $value['title']
            ];      
        }

        return $specialities;
    }
}
