<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\JobController;
use App\Models\Job;
use App\Models\Speciality;

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

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $projectId = $_GET['project_id'];
        self::$projectId = $projectId;

        return [];
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
                    Matrix::make('jobs')
                        ->columns([
                            'Работа',
                            'Количество часов',
                            'Сотрудники'
                        ])
                        ->title('Список работ')
                        ->fields([
                            'Работа' => Select::make('category')
                                                ->fromModel(Speciality::class, 'title'),
                            'Количество часов' => Input::make()->type('number')->min(0),
                            //'Сотрудники' => 
                        ])
                        ->maxRows(1)
                        ->required(),
                ])
            ])
        ];
    }
}
