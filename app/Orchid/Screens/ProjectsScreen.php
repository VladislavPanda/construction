<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Illuminate\Support\Str;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Http\Controllers\ProjectController;

class ProjectsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Объекты';
    public $permission = 'platform.projects';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'projects' => Project::paginate()
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
            Layout::table('projects', [
                TD::make('', 'Адрес')
                    ->width('400')
                    ->render(function (Project $project) {
                        return Str::limit($project->address);
                }),

                TD::make('', 'Описание')
                    ->width('400')
                    ->render(function (Project $project) {
                        return Str::limit($project->description);
                }),

                TD::make('', 'Дата сдачи')
                    ->width('400')
                    ->render(function (Project $project) {
                        return Str::limit($project->end_date);
                }),

                TD::make('', 'Статус')
                    ->width('400')
                    ->render(function (Project $project) {
                        return Str::limit($project->status);
                }),

                TD::make('', 'Прораб')
                    ->width('400')
                    ->render(function (Project $project) {
                        return Str::limit($project->foreman);
                }),

                TD::make('', '')
                    //->width('200')
                    ->render(function (Project $project) {
                        return Group::make([
                            Button::make('Список работ')
                                    ->method('jobs')
                                    //->type(Color::PRIMARY())
                                    ->class('shortBtn')
                                    ->parameters([
                                        'id' => $project->id,
                                    ]),

			                /*Button::make('Назначить прораба')
                                    ->method('setForeman')
                                    //->type(Color::PRIMARY())
                                    ->class('shortBtn')
                                    ->parameters([
                                        'id' => $project->id,
                                        //'pageId' => self::$page
                                    ]),*/

                            Button::make('Редактировать')
                                    ->method('update')
                                    ->type(Color::PRIMARY())
                                    ->class('shortBtn')
                                    ->parameters([
                                        'id' => $project->id,
                                        //'pageId' => self::$page
                                    ]),
                        ])->autoWidth();
                    }),
            ])
        ];
    }

    public function jobs(Request $request){
        $projectId = $request->get('id');

        return redirect()->route('platform.projectJobs', ['project_id' => $projectId]);
    }

    /*public function setForeman(Request $request){
        $projectId = $request->get('id');

        return redirect()->route('platform.projectForemanSet', ['project_id' => $projectId]);
    }*/

    public function update(Request $request){
        $projectId = $request->get('id');

        return redirect()->route('platform.projectUpdate', ['project_id' => $projectId]);
    }
}
