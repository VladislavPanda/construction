<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Illuminate\Support\Facades\Date;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Alert;
use App\Http\Controllers\ProjectController;

use App\Models\Speciality;

class ProjectAddScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Добавить объект';
    public $permission = 'platform.projectAdd';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
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
                    Input::make('address')
                        ->title('Адрес:')
                        ->required(),

                    TextArea::make('description')
                        ->title('Описание')
                        ->rows(6)
                        ->required(),

                    /*DateTimer::make('start_date')
                        ->title('Стартовая дата:')
                        ->format('d-m-Y')
                        ->required()
                        ->available([ ['from' => Date::today(), "to" => Date::maxValue()] ]),
*/
                    DateTimer::make('end_date')
                        ->title('Дата сдачи:')
                        ->format('d-m-Y')
                        ->required()
                        ->available([ ['from' => Date::today(), "to" => Date::maxValue()] ]),    

                        Matrix::make('jobs')
                        ->columns([
                            'Работа',
                            'Количество часов',
                        ])
                        ->title('Список работ')
                        ->fields([
                            'Работа' => Select::make('category')
                                                ->fromModel(Speciality::class, 'title')
                        ])
                        ->required(),

                    Button::make('Добавить')
                        ->method('submit')
                        ->type(Color::DEFAULT())
                ])
            ])
        ];
    }

    public function submit(Request $request){
        $flag = false;
        $projectData = $request->all();

        $controller = new ProjectController();
        $flag = $controller->store($projectData);

        if($flag === true) Alert::warning('Объект успешно сохранён');
    }
}
