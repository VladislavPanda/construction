<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Speciality;

class SpecialityController extends Controller
{
    public function store($specialityData){
        Speciality::create($specialityData);

        return true;
    }

    public function getCurrentData($id){
        $specialityForUpdate = Speciality::find($id);
        
        return $specialityForUpdate;
    }

    public function update($specialityUpdateData, $id){
        $speciality = Speciality::find($id); // находим редактируемый объект
        $speciality->update(['title' => $specialityUpdateData['speciality']]);

        // После проверок
        return true;
    } 

    public function delete($id){
        $speciality = Speciality::find($id); // находим удаляемый объект
        $speciality->delete();
    }

    // Текущий список специальностей для использования в других контроллерах
    public function getSpecialities(){
        $specialitiesList = [];
        $specialities = Speciality::all()->toArray();

        foreach($specialities as $key => $value){
            $specialitiesList[$key] = [
                'id' => $value['id'],
                'title' => $value['title']
            ];
        }
       
        return $specialitiesList;
    }
}
