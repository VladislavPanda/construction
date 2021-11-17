<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Speciality;

class ProjectController extends Controller
{

    // Сохраняем запись объекта
    public function store($project){
        // Нужна проверка, не пустая ли матрица
        $flag = false;
        $specialitiesList = [];

        // Получение названий специальностей из кодов
        $speciality = new SpecialityController();
        $specialitiesList = $speciality->getSpecialities();
        
        foreach($project['jobs'] as $key => &$value){
            foreach($specialitiesList as $k => $v){
                if($value['Работа'] == $v['id']) $value['Работа'] = $v['title'];
            }
        }

        // Преобразование массива в json
        $project['jobs'] = json_encode(array_values($project['jobs']), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        Project::create($project);

        return true;
    } 
}
