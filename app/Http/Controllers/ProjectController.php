<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthHandler;
use App\Models\Project;
use App\Models\Speciality;
use App\Models\ProjectForeman;
use App\Models\User;
use App\Models\Job;
use App\Models\BudgetBid;
use PDF;

class ProjectController extends Controller
{
    private static $statuses = [
        'set' => 'В работе',
        'closed' => 'Закрыт'
    ];
    // Сохраняем запись объекта
    public function store($project){
        $projectId = 1;
        $flag = false;
        $specialitiesList = [];
        $foremanData = [];
        
        $eSort = $project['end_date'];
        $eSort = str_replace('-', '/', $eSort);
        $eSort = explode('/', $eSort);
        $eSortNew = $eSort[2] . '/' . $eSort[1] . '/' . $eSort[0];
        $project['end_date'] = $eSortNew;
        
        if(Project::exists()){
            // Запись в таблицу связей
            $lastProjectId = Project::latest()->first()->id;
            $projectId = $lastProjectId + 1;
        }

        //dd($project);
        $foremanData = explode(' ', $project['foreman']);
        $foremanId = User::select('id')->where('surname', $foremanData[0])->get()->toArray();
        /*dd($foremanId);*/
        $foremanId = $foremanId[0]['id'];
        $project['user_id'] = $foremanId;

        Project::create($project);
        ProjectForeman::create(['project_id' => $projectId, 'foreman_id' => $foremanId]);

        return true;
    } 

    public function update($updatedProject){
        $flag = false;
        $token = $updatedProject['_token'];
        unset($updatedProject['_token']);
        $projectId = $updatedProject['id'];

        $eSort = $updatedProject['end_date'];
        $eSort = str_replace('-', '/', $eSort);
        $eSort = explode('/', $eSort);
        $eSortNew = $eSort[2] . '/' . $eSort[1] . '/' . $eSort[0];
        $updatedProject['end_date'] = $eSortNew;

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

    /*public function getMyProject(){
        $foremanId = AuthHandler::getCurrentUser();

        $project = Project::where('user_id', $foremanId)->get()->toArray();
        $project = $project[0];
        $jobs = Job::where('project_id', $project['id'])->get()->toArray();

        $project['jobs'] = $jobs;

        return $project;
    }*/

    public function close($projectId){
        if($this->closeValidator($projectId) === false) return false;
        else{ 
            $project = Project::find($projectId);
            $project->update(['status' => self::$statuses['closed']]);

            $pdf = PDF::loadView('report', compact(['project']));
            $pdf->save('project_' . $projectId . '.pdf');
                    
            return $pdf;
        }
    }

    private function closeValidator($projectId){
        $closeFlag = true;
        $jobs = Job::select('status')->where('project_id', $projectId)->get()->toArray();
        
        foreach($jobs as $key => $value){
            if(in_array(self::$statuses['set'], $value)) $closeFlag = false;
        }

        return $closeFlag;
    }

    public function updateDifficulty($data){
        $item = Project::find($data['project_id']);
        $item->update([
            'difficulty' => (int) $data['difficulty']
        ]);
    }

    public function setBudget($projectId, $budget){
        $item = Project::find($projectId);
        $item->update([
            'budget' => $budget
        ]);
    }

    public function updateBudget($budgetBid){
        $budgetBidObject = BudgetBid::find($budgetBid['bidId']);

        if($budgetBid['result'] == 'Одобрить'){
            $budgetBidObject->update([
                'status' => 'Одобрено'
            ]);

            $projectObj = Project::find($budgetBid['project_id']);
            $projectData = $projectObj->toArray();
            $newBudget = $projectData['budget'] - $budgetBid['sum'];

            $projectObj->update([
                'budget' => $newBudget
            ]);
        }else if($budgetBid['result'] == 'Отклонить'){
            $budgetBidObject->update([
                'status' => 'Отклонено'
            ]);
        }
    } 
}
