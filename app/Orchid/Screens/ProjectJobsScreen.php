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
            'jobs' => Job::where('project_id', $projectId)->paginate()
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
                ])
            ]), 

            Layout::table('jobs', [
                TD::make('', 'Описание')
                    ->width('400')
                    ->render(function (Job $job) {
                        return Str::limit($job->description);
                }),

                TD::make('', 'Дата завершения')
                    ->width('400')
                    ->render(function (Job $job) {
                        return Str::limit($job->date);
                }),

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

                /*TD::make('', 'Исполнитель')
                    ->width('400')
                    ->render(function (Job $job) {
                        return Str::limit($job->status);
                }),*/

                /*TD::make('', 'Прораб')
                    ->width('400')
                    ->render(function (Job $job) {
                        return Str::limit($project->foreman);
                }),*/

                TD::make('', '')
                    //->width('200')
                    ->render(function (Job $job) {
                        return Group::make([
                            Button::make('Редактировать')
                                    ->method('update')
                                    ->type(Color::PRIMARY())
                                    //->class('shortBtn')
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
}