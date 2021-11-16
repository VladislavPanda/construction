<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;

class WorkerController extends Controller
{
    // Сохранение сведений
    public function store($workerData){
        // Обработка специальностей
        $specialitiesList = [];
        $currentSpeciality = '';

        $specialityController = new SpecialityController();
        $specialitiesList = $specialityController->getSpecialities();

        foreach($specialitiesList as $key => $value){
            if($workerData['speciality'][0] == $value['id']) $currentSpeciality = $value['title'];
        }

        $workerData['speciality'] = $currentSpeciality;
        $workerData['account_status'] = 1;
        
        // Сохранение объекта
        Worker::create($workerData);

        return true;
    }
}
