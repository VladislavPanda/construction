<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Support\Facades\Alert;
use Illuminate\Support\Facades\Date;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Matrix;
use Illuminate\Http\Request;

use App\Http\Controllers\ProjectController;

class ProjectUpdateScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Редактировать объект';
    public $permission = 'platform.projectUpdate';
    private static $projectId;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $project = [];
        $jobs = [];
        $projectId = $_GET['project_id'];
        self::$projectId = $projectId;
        
        $controller = new ProjectController();
        $project = $controller->getUpdatedProject($projectId);

        $jobs = json_decode($project['jobs'], true);

        return [
            'address' => $project->address,
            'description' => $project->description,
            'end_date' => $project->end_date,
            'status' => $project->status,
            'foreman' => $project->foreman,
            'jobs' => $jobs,
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
            Layout::columns([
                Layout::rows([
                    Input::make('address')
                        ->title('Адрес:')
                        ->required(),

                    TextArea::make('description')
                        ->title('Описание')
                        ->rows(6)
                        ->required(),
                    
                    DateTimer::make('end_date')
                        ->title('Дата сдачи:')
                        ->format('d-m-Y')
                        ->required()
                        ->available([ ['from' => Date::today(), "to" => Date::maxValue()] ]),    

                    /*Matrix::make('jobs')
                        ->columns([
                            'Работа',
                            'Количество часов',
                        ])
                        ->title('Список работ')
                        ->fields([
                            'jobs',
                            'Количество часов' => Input::make()->type('number')->min(0),
                        ])
                        ->required(),*/

                    Button::make('Редактировать')
                        ->method('submit')
                        ->type(Color::DEFAULT())
                        ->parameters([
                            'id' => self::$projectId
                        ])
                ])
            ])
        ];
    }

    public function submit(Request $request){
        $flag = false;
        $project = $request->all();

        $controller = new ProjectController();
        $flag = $controller->update($project);

        if($flag === true) Alert::warning('Запись успешно отредактирована');
    }

    public function back(Request $request){
        $projectId = $request->get('project_id');

        return redirect()->route('platform.projectJobs', ['project_id' => $projectId]);
    }
}
