<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Illuminate\Support\Str;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Orchid\Support\Facades\Alert;
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
            'projects' => Project::filters()->where('status', '!=' , 'Закрыт')->paginate()
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
                TD::make('address', 'Адрес')
                    ->width('400')
                    ->render(function (Project $project) {
                        //return Str::limit($project->address);
                        return view('tableData', ['data' => $project->address]);
                }),

                TD::make('', 'Описание')
                    ->width('400')
                    ->render(function (Project $project) {
                        //return Str::limit($project->description);
                        return view('tableData', ['data' => $project->description]);
                }),

                TD::make('end_date', 'Дата сдачи')
                    ->width('400')
                    ->render(function (Project $project) {
                        $currentDate = date('Y-m-d');
                        //return Str::limit($project->end_date);
                        $date = str_replace('00:00:00', '', $project->end_date);
                        $date = explode('-', $date);
                        $date = $date[2] . '-' . $date[1] . '-' . $date[0];
                        $date = str_replace(' ', '', $date);

                        if($currentDate >= $project->end_date) $date .= '<p> Срок сдачи объекта истёк, обновите дату</p>';
                        return $date;
                })->sort(),

                TD::make('status', 'Статус')
                    ->width('400')
                    ->render(function (Project $project) {
                        return Str::limit($project->status);
                })->sort(),

                TD::make('', 'Прораб')
                    ->width('300')
                    ->render(function (Project $project) {
                        //return Str::limit($project->foreman);
                        return view('tableData', ['data' => $project->foreman]);
                }),

                TD::make('', '')
                    //->width('200')
                    ->render(function (Project $project) {
                        return Group::make([
                            Button::make('Работы на объекте')
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

                            Button::make('Закрыть объект')
                                ->method('close')
                                ->type(Color::PRIMARY())
                                ->class('shortBtn')
                                ->parameters([
                                    'id' => $project->id,
                                    //'pageId' => self::$page
                                ])
                                ->rawClick(),
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

    public function close(Request $request){
        $flag = false;
        $projectId = $request->get('id');

        $controller = new ProjectController();
        $flag = $controller->close($projectId);
        if($flag !== false){
            Alert::warning('Объект был успешно закрыт');

            return $flag->download('report_' . $projectId . '.pdf');
        }
        else Alert::warning('Ошибка завершения. На объекте выполнены не все работы');
    }
}
