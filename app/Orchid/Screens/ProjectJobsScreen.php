<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\TD;
use Illuminate\Support\Str;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Alert;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\JobController;
use App\Models\Job;
use App\Models\User;
use App\Models\Speciality;

class ProjectJobsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Работы на объекте';
    public $permission = 'platform.projectJobs';
    private static $projectId; 

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $projectId = $_GET['project_id'];
        self::$projectId = $projectId;

        return [
            'jobs' => Job::filters()->where('project_id', $projectId)->paginate()
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                Group::make([
                    Button::make('Добавить')
                        ->method('projectJobsAdd')
                        ->route('platform.projectJobsAdd')
                        ->type(Color::PRIMARY())
                        ->parameters([
                            'project_id' => self::$projectId
                        ]),
                    
                    Button::make('Назад')
                        ->method('back')
                        ->type(Color::DEFAULT())
                        ->parameters([
                            'project_id' => self::$projectId
                        ]),
                ])->autowidth()
            ]), 

            Layout::table('jobs', [
                TD::make('description', 'Описание')
                    ->width('400')
                    ->render(function (Job $job) {
                        return Str::limit($job->description);
                }),

                TD::make('date', 'Дата завершения')
                    ->width('400')
                    ->render(function (Job $job) {
                        //return Str::limit($job->date);
                        $date = str_replace('00:00:00', '', $job->date);
                        $date = explode('-', $date);
                        $date = $date[2] . '-' . $date[1] . '-' . $date[0];
                        $date = str_replace(' ', '', $date);
                        return $date;
                })->sort(),

                TD::make('', 'Вид работ')
                    ->width('400')
                    ->render(function (Job $job) {
                        $speciality = User::select('speciality')->where('id', $job->worker_id)->get()->toArray();
                        $speciality = $speciality[0]['speciality'];

                        return Str::limit($speciality);
                }),

                TD::make('', 'Исполнитель')
                    ->width('400')
                    ->render(function (Job $job) {
                        $worker = User::select('surname')->where('id', $job->worker_id)->get()->toArray();
                        $worker = $worker[0]['surname'];

                        return Str::limit($worker);
                }),

                TD::make('status', 'Статус')
                    ->width('400')
                    ->render(function (Job $job) {
                        return Str::limit($job->status);
                })->sort(),

                TD::make('', 'Комментарий')
                    ->width('400')
                    ->render(function (Job $job) {
                        return Str::limit($job->reject_reason);
                }),

                TD::make('', '')
                    //->width('200')
                    ->render(function (Job $job) {
                        return Group::make([
                            Button::make('Редактировать')
                                    ->method('update')
                                    //->type(Color::PRIMARY())
                                    ->class('shortBtn')
                                    ->parameters([
                                        'id' => $job->id,
                                        //'pageId' => self::$page
                                    ]),

                            Button::make('Удалить')
                                    ->method('delete')
                                    //->type(Color::PRIMARY())
                                    ->class('shortBtn')
                                    ->parameters([
                                        'id' => $job->id,
                                        //'pageId' => self::$page
                                    ]),
                        ])->autoWidth();
                    }),
            ])
        ];
    }

    public function projectJobsAdd(Request $request){
        $projectId = $request->get('project_id');

        return redirect()->route('platform.projectJobsAdd', ['project_id' => $projectId]);
    }   

    public function update(Request $request){
        $jobId = $request->get('id');

        return redirect()->route('platform.projectJobUpdate', ['job_id' => $jobId]);
    }

    public function delete(Request $request){
        $flag = false;
        $jobId = $request->get('id');

        $controller = new JobController();
        $flag = $controller->delete($jobId);

        if($flag === true) Alert::warning('Задача была удалена');
    }

    public function back(Request $request){
        $projectId = $request->get('projects');

        return redirect()->route('platform.projects');
    }
}