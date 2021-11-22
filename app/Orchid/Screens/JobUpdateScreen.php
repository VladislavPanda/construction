<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Illuminate\Support\Facades\Date;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Fields\Select;
use App\Http\Controllers\JobController;
use App\Models\User;
use App\Models\Project;

class JobUpdateScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Редактировать работу';
    public $permission = 'platform.projectJobUpdate';
    private static $jobId;
    private static $workers;
    private static $currentWorker;
    private static $projectEndDate;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $job = [];
        $worker = [];
        $jobId = $_GET['job_id'];
        self::$jobId = $jobId;        

        $controller = new JobController();
        $job = $controller->getUpdatedJob($jobId);

        $endDate = Project::select('end_date')->where('id', $job->project_id)->get()->toArray();
        self::$projectEndDate = $endDate[0]['end_date'];

        $workers = User::select('surname', 'first_name', 'patronymic', 'speciality')->whereHas('roles', function ($query) {
            $query->where('slug', 'worker');
        })->get()->toArray();

        foreach($workers as $key => $value){
            $keys = $value['surname'] . " " . $value['first_name'] . " " . $value['patronymic'] . " - " . $value['speciality'];
            self::$workers[$keys] = $value['surname'] . " " . $value['first_name'] . " " . $value['patronymic'] . " - " . $value['speciality'];
        }

        $worker = User::select('surname', 'first_name', 'patronymic', 'speciality')->where('id', $job->worker_id)->get()->toArray();
        $currentWorker = $worker[0]['surname'] . " " . $worker[0]['first_name'] . " " . $worker[0]['patronymic'] . " - " . $worker[0]['speciality'];

        return [
            'description' => $job->description,
            'date' => $job->date,
            'job' => $job->job,
            'created_at' => $job->created_at,
            'worker' => $currentWorker
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
                    TextArea::make('description')
                        ->title('Описание')
                        ->rows(6)
                        ->required(),

                    Select::make('worker')
                        ->title('Назначить сотрудника')
                        ->value('worker')
                        ->options(self::$workers)
                        ->required(),

                    DateTimer::make('date')
                        ->title('Дата выполнения:')
                        ->format('d-m-Y')
                        ->required()
                        ->available([ ['from' => Date::today(), "to" => self::$projectEndDate] ]),

                    Button::make('Редактировать')
                        ->method('submit')
                        ->type(Color::DEFAULT())
                        ->parameters([
                            'id' => self::$jobId
                        ]),
                ])
            ])
        ];
    }

    public function submit(Request $request){
        $flag = false;
        $updatedJob = $request->all();

        $controller = new JobController();
        $flag = $controller->update($updatedJob);

        if($flag === true) Alert::warning('Задача была успешно отредактирована');
    }
}
