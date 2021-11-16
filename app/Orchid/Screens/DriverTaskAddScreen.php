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
use App\Http\Controllers\TaskController;

class DriverTaskAddScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Назначить задачу водителю';

    public $permission = 'platform.driverTaskAdd';

    private static $driverId;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $driverId = $_GET['driver_id']; 
        self::$driverId = $driverId;

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

                    Input::make('title')
                        ->title('Название:')
                        ->required(),

                    TextArea::make('description')
                        ->title('Описание')
                        ->rows(6)
                        ->required(),

                    DateTimer::make('start_date')
                        ->title('Стартовая дата:')
                        ->format('d-m-Y')
                        ->required()
                        ->available([ ['from' => Date::today(), "to" => Date::maxValue()] ]),

                    DateTimer::make('end_date')
                        ->title('Конечная дата:')
                        ->format('d-m-Y')
                        ->required()
                        ->available([ ['from' => Date::today(), "to" => Date::maxValue()] ]),    

                    Button::make('Добавить')
                        ->method('submit')
                        ->type(Color::DEFAULT())
                        ->parameters([
                            'driver_id' => self::$driverId
                        ]),
                ])
            ])
        ];
    }

    public function submit(Request $request){
        $flag = false;
        $driverTask = $request->all();

        $controller = new TaskController();
        $flag = $controller->store($driverTask);

        if($flag === true) Alert::warning('Задача успешно добавлена');
    }
}
