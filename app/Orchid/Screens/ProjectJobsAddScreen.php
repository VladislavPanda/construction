<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Illuminate\Support\Facades\Date;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Alert;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\JobController;
use App\Models\User;
use App\Models\Job;
use App\Models\Speciality;
use App\Models\Project;

class ProjectJobsAddScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Добавить работы';
    public $permission = 'platform.projectJobsAdd';
    private static $projectId;
    private static $workers;
    private static $projectEndDate;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $workersList = [];
        $projectId = $_GET['project_id'];
        self::$projectId = $projectId;

        $endDate = Project::select('end_date')->where('id', $projectId)->get()->toArray();
        //self::$projectEndDate = $endDate[0]['end_date'];

        $eSort = $endDate[0]['end_date'];
        $eSort = str_replace('-', '/', $eSort);
        $eSort = explode('/', $eSort);
        self::$projectEndDate = $eSort[2] . '/' . $eSort[1] . '/' . $eSort[0];

        $workers = User::select('surname', 'first_name', 'patronymic', 'speciality')->whereHas('roles', function ($query) {
            $query->where('slug', 'worker');
        })->get()->toArray();

        foreach($workers as $key => $value){
            $keys = $value['surname'] . " " . $value['first_name'] . " " . $value['patronymic'] . " - " . $value['speciality'];
            self::$workers[$keys] = $value['surname'] . " " . $value['first_name'] . " " . $value['patronymic'] . " - " . $value['speciality'];
        }

        return [
            'worker' => self::$workers
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
                Button::make('Назад')
                            ->method('back')
                            ->type(Color::DEFAULT())
                            ->parameters([
                                'project_id' => self::$projectId
                            ]),
            ]),

            Layout::columns([
                Layout::rows([
                    TextArea::make('description')
                        ->title('Описание')
                        ->rows(6),
                        //->required(),
                    
                    DateTimer::make('date')
                        ->title('Дата завершения:')
                        ->format('d-m-Y')
                        //->required()
                        ->available([ ['from' => Date::today(), "to" => self::$projectEndDate] ]), 

                    Select::make('worker')
                        ->title('Назначить сотрудника')
                        ->options(self::$workers),
                        //->required(),
                        //->fromModel(Speciality::class, 'title'),*/

                    Button::make('Добавить')
                        ->method('submit')
                        ->type(Color::DEFAULT())
                        ->parameters([
                            'project_id' => self::$projectId
                        ])
                ])
            ])
        ];
    }

    public function submit(Request $request){
        $flag = false;
        $job = $request->all();

        if($job['description'] == null || $job['date'] == null){
            Alert::warning('Заполните поля "Описание" и "Дата завершения"');
        }else{
            $controller = new JobController();
            $flag = $controller->store($job);
    
            if($flag === true) Alert::warning('Задача была успешно добавлена');
        }
    }

    public function back(Request $request){
        $projectId = $request->get('project_id');

        return redirect()->route('platform.projectJobs', ['project_id' => $projectId]);
    }
}
