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
use App\Models\Task;
use App\Services\AuthHandler;
//use App\Http\Controllers\WorkerController;

class DriverTasksScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Мои задачи';
    public $permission = 'platform.driverTasks';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $driverId = AuthHandler::getCurrentUser();

        return [
            'tasks' => Task::where('driver_id', $driverId)->paginate()
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
            Layout::table('tasks', [
                TD::make('', 'Адрес')
                    //->width('400')
                    ->render(function (Task $task) {
                        return Str::limit($task->address);
                }),

                TD::make('', 'Название задачи')
                    //->width('400')
                    ->render(function (Task $task) {
                        return Str::limit($task->title);
                }),

                TD::make('', 'Описание')
                    //->width('400')
                    ->render(function (Task $task) {
                        return Str::limit($task->description);
                }),

                TD::make('', 'Дата начала')
                    //->width('400')
                    ->render(function (Task $task) {
                        return Str::limit($task->start_date);
                }),

                TD::make('', 'Дата завершения')
                    //->width('400')
                    ->render(function (Task $task) {
                        return Str::limit($task->end_date);
                }),

                /*TD::make('update', '')
                    //->width('200')
                    ->render(function (Task $task) {
                        return Group::make([
                            Button::make('Редактировать')
                                    ->method('updateSpeciality')
                                    ->type(Color::PRIMARY())
                                    ->parameters([
                                        'id' => $speciality->id,
                                    ]),
                        ])->autoWidth();
                    }),

                TD::make('delete', '')
                    //->width('200')
                    ->render(function (Task $task) {
                        return Group::make([
                            Button::make('Удалить')
                                    ->method('deleteSpeciality')
                                    ->type(Color::PRIMARY())
                                    ->parameters([
                                        'id' => $speciality->id,
                                    ]),
                        ])->autoWidth();
                    }),*/
            ])
        ];
    }
}
