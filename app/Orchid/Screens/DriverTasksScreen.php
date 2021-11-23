<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\TD;
use Illuminate\Support\Str;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\TextArea;
use App\Services\AuthHandler;
use App\Http\Controllers\TaskController;

use App\Models\Task;

class DriverTasksScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Задачи водителя';

    public $permission = 'platform.driverTasks';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $driverId = $_GET['driver_id'];

        return [
            'tasks' => Task::filters()->where('driver_id', $driverId)->paginate()
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
                TD::make('address', 'Адрес')
                    //->width('400')
                    ->render(function (Task $task) {
                        //return Str::limit($task->address);
                        return view('tableData', ['data' => $task->address]);
                })->sort(),

                TD::make('', 'Название задачи')
                    //->width('400')
                    ->render(function (Task $task) {
                        return Str::limit($task->title);
                }),

                TD::make('', 'Описание')
                    //->width('400')
                    ->render(function (Task $task) {
                        //return Str::limit($task->description);
                        return view('tableData', ['data' => $task->description]);
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

                TD::make('status', 'Статус')
                    //->width('400')
                    ->render(function (Task $task) {
                        return Str::limit($task->status);
                })->sort(),

                TD::make('', 'Причина отклонения')
                    //->width('400')
                    ->render(function (Task $task) {
                        //return Str::limit($task->reject_reason);
                        return view('tableData', ['data' => $task->reject_reason]);
                }),

                TD::make('', '')
                    //->width('200')
                    ->render(function (Task $task) {
                        return Group::make([
                            Button::make('Редактировать')
                                    ->method('update')
                                    ->type(Color::PRIMARY())
                                    //->class('longDocumentBtn')
                                    ->parameters([
                                        'id' => $task->id,
                                    ]),
                        ])->autoWidth();
                    }),
            ]),
        ];
    }

    public function update(Request $request){
        $taskId = $request->get('id');

        return redirect()->route('platform.driverTaskUpdate', ['task_id' => $taskId]);
    }
}
