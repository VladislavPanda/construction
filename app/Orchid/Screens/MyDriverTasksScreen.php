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
use App\Models\Task;
use App\Services\AuthHandler;
use App\Http\Controllers\TaskController;

class MyDriverTasksScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Мои задачи';
    public $permission = 'platform.myDriverTasks';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $driverId = AuthHandler::getCurrentUser();

        return [
            'tasks' => Task::where('driver_id', $driverId)->where('status', 'В работе')->paginate()
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

                TD::make('', '')
                    //->width('200')
                    ->render(function (Task $task) {
                        return Group::make([
                            Button::make('Выполнено')
                                    ->method('done')
                                    ->type(Color::PRIMARY())
                                    //->class('longDocumentBtn')
                                    ->parameters([
                                        'id' => $task->id,
                                    ]),

                            ModalToggle::make('Отклонить')
                                    ->type(Color::PRIMARY())
                                    //->class('longDocumentBtn')
                                    ->modal('reject_reason_modal')
                                    ->parameters([
                                        'id' => $task->id,
                                    ])
                                    ->method('reject')
                        ])->autoWidth();
                    }),
            ]),

            Layout::modal('reject_reason_modal', Layout::rows([
                TextArea::make('reject_reason')
                        //->title('Комментарий:')
                        ->rows(6),
                //Input::make('toast')
                    //->title('Messages to display')
                    //->placeholder('Hello world!')
                    //->help('The entered text will be displayed on the right side as a toast.')
                  //  ->required(),
            ]))->title('Введите причину невыполнения')->applyButton('Отправить')
            ->closeButton('Закрыть'),
        ];
    }

    public function done(Request $request){
        $flag = false;
        $taskId = $request->get('id');

        $controller = new TaskController();
        $flag = $controller->setDone($taskId);

        if($flag === true) Alert::warning('Задача отмечена как выполненная');
    }

    public function reject(Request $request){
        $flag = false;
        $taskId = $request->get('id');
        $rejectReason = $request->get('reject_reason');
    
        $controller = new TaskController();
        $flag = $controller->setReject($taskId, $rejectReason);

        
    }
}
