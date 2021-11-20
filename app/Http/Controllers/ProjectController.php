<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Speciality;
use App\Models\ProjectForeman;
use App\Models\User;

class ProjectController extends Controller
{
    // Сохраняем запись объекта
    public function store($project){
        // Нужна проверка, не пустая ли матрица
        $flag = false;
        $specialitiesList = [];
        $foremanData = [];
        
        // Запись в таблицу связей
        $lastProjectId = Project::latest()->first()->id;
        $projectId = $lastProjectId + 1;

        $foremanData = explode(' ', $project['foreman']);
        $foremanId = User::select('id')->where('surname', $foremanData[0])->get()->toArray();
        $foremanId = $foremanId[0]['id'];

        // Получение названий специальностей из кодов
        $speciality = new SpecialityController();
        //$specialitiesList = $speciality->getSpecialities();
        
        /*foreach($project['jobs'] as $key => &$value){
            foreach($specialitiesList as $k => $v){
                if($value['Работа'] == $v['id']) $value['Работа'] = $v['title'];
            }
        }

        // Преобразование массива в json
        $project['jobs'] = json_encode(array_values($project['jobs']), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        */

        Project::create($project);
        ProjectForeman::create(['project_id' => $projectId, 'foreman_id' => $foremanId]);

        return true;
    } 

    public function update($updatedProject){
        $flag = false;
        $token = $updatedProject['_token'];
        unset($updatedProject['_token']);
        $projectId = $updatedProject['id'];
        //$updatedProject['jobs'] = json_encode($updatedProject['jobs'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        $project = Project::where('id', $projectId);
        $project->update($updatedProject);       
        
        return true;
    }

    public function getProjectJobs($projectId){
        $jobs = Project::select('jobs')->where('id', $projectId)->get()->toArray();

        $jobs = json_decode($jobs[0]['jobs'], true);

        return $jobs;
    }

    public function getUpdatedProject($projectId){
        $project = Project::find($projectId);

        return $project;
    }
}
