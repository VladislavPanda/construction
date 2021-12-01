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
use App\http\Controllers\TaskController;

class DriverTaskUpdateScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Редактировать задачу';
    public $permission = 'platform.driverTaskUpdate';
    private static $taskId;
    private static $driverId;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $task = [];
        $taskId = $_GET['task_id'];
        self::$taskId = $taskId;

        $controller = new TaskController();
        $task = $controller->getCurrentTask($taskId);

        self::$driverId = $task->driver_id;

        return [
            'id' => $task->id,
            'address' => $task->address,
            'title' => $task->title,
            'description' => $task->description,
            'start_date' => $task->start_date,
            'end_date' => $task->end_date
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
                                'driver_id' => self::$driverId
                            ]),
            ]),

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

                    Button::make('Редактировать')
                        ->method('submit')
                        ->type(Color::DEFAULT())
                        ->parameters([
                            'id' => self::$taskId
                        ]),
                ])
            ])
        ];
    }

    public function submit(Request $request){
        $flag = false;
        $updatedTask = $request->all();

        $controller = new TaskController();
        $flag = $controller->update($updatedTask);
        
        if($flag === true) Alert::warning('Задача была успешно отредактирована'); 
    }

    public function back(Request $request){
        $driverId = $request->get('driver_id');

        return redirect()->route('platform.driverTasks', ['driver_id' => $driverId]);
    }
}
