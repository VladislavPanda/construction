<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthHandler;
use App\Models\Project;
use App\Models\Speciality;
use App\Models\ProjectForeman;
use App\Models\User;

class ProjectController extends Controller
{
    // Сохраняем запись объекта
    public function store($project){
        $projectId = 1;
        $flag = false;
        $specialitiesList = [];
        $foremanData = [];
        
        if(Project::exists()){
            // Запись в таблицу связей
            $lastProjectId = Project::latest()->first()->id;
            $projectId = $lastProjectId + 1;
        }

        $foremanData = explode(' ', $project['foreman']);
        $foremanId = User::select('id')->where('surname', $foremanData[0])->get()->toArray();
        $foremanId = $foremanId[0]['id'];

        Project::create($project);
        ProjectForeman::create(['project_id' => $projectId, 'foreman_id' => $foremanId]);

        return true;
    } 

    public function update($updatedProject){
        $flag = false;
        $token = $updatedProject['_token'];
        unset($updatedProject['_token']);
        $projectId = $updatedProject['id'];

        $project = Project::where('id', $projectId);
        $project->update($updatedProject);       
        
        return true;
    }

    /*public function getProjectJobs($projectId){
        $jobs = Project::select('jobs')->where('id', $projectId)->get()->toArray();

        $jobs = json_decode($jobs[0]['jobs'], true);

        return $jobs;
    }*/

    public function getUpdatedProject($projectId){
        $project = Project::find($projectId);

        return $project;
    }

    public function getMyProject(){
        $foremanId = AuthHandler::getCurrentUser();

        $project = Project::whereHas('ProjectForemen', function ($query) {
                $query->where('foreman_id', $foremanId);
        });

        dd($project);
    }
}
