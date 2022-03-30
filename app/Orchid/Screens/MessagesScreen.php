<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Alert;
use Illuminate\Support\Facades\Db;
use Orchid\Screen\TD;
use Illuminate\Support\Str;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\TextArea;
use App\Services\AuthHandler;
use Orchid\Screen\Repository;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\JobController;
use App\Models\Job;
use App\Models\User;
use App\Models\Speciality;
use App\Models\Task;
use App\Models\WorkerMessage;

class MessagesScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Оповещения';
    public $permission = 'platform.messages';
    private $messages = [];

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $messagesRepository = [];
        $this->messages = Db::table('users')
                            ->join('worker_messages', 'users.id', '=', 'worker_messages.user_id')
                            ->select('users.first_name', 'users.surname', 'users.patronymic', 'worker_messages.*')->get()->toArray();

        for($i = 0; $i < sizeof($this->messages); $i++){
            $messagesRepository[] = new Repository([
                                       'id' => $this->messages[$i]->id,
                                       'title' => $this->messages[$i]->title,
                                       'content' => $this->messages[$i]->content,
                                       'first_name' => $this->messages[$i]->first_name,
                                       'surname' => $this->messages[$i]->surname,
                                       'patronymic' => $this->messages[$i]->patronymic
                                       ]);                           
        }
    
        return [
            'tasks' => Task::filters()->where('status', 'Не выполнено')->paginate(), // Невыполненные задачи водителя
            'jobs' => Job::filters()->where('status', 'Не выполнено')->paginate(), // Невыполненные работы  
            //'workerMessages' => WorkerMessage::with('worker')->paginate()
            'workerMessages' => $messagesRepository
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
                    }),
    
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
    
                    TD::make('start_date', 'Дата начала')
                        //->width('400')
                        ->render(function (Task $task) {
                            //return Str::limit($task->start_date);
                            $date = str_replace('00:00:00', '', $task->start_date);
                            $date = explode('-', $date);
                            $date = $date[2] . '-' . $date[1] . '-' . $date[0];
                            $date = str_replace(' ', '', $date);
                            return $date;
                    }),
    
                    TD::make('end_date', 'Дата завершения')
                        //->width('400')
                        ->render(function (Task $task) {
                            //return Str::limit($task->end_date);
                            $date = str_replace('00:00:00', '', $task->end_date);
                            $date = explode('-', $date);
                            $date = $date[2] . '-' . $date[1] . '-' . $date[0];
                            $date = str_replace(' ', '', $date);
                            return $date;
                    })->sort(),
    
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
                                Link::make('Перейти')
                                    ->type(Color::DARK())
                                    ->route('platform.driverTaskUpdate', ['task_id' => $task->id])
                            ])->autoWidth();
                        }),
                ])->title('Задачи водителя'),
    
                Layout::table('jobs', [
                    TD::make('description', 'Описание')
                        ->width('400')
                        ->render(function (Job $job) {
                            return Str::limit($job->description);
                    }),
    
                    TD::make('date', 'Дата завершения')
                        ->width('400')
                        ->render(function (Job $job) {
                            //return Str::limit($job->date);
                            $date = str_replace('00:00:00', '', $job->date);
                            $date = explode('-', $date);
                            $date = $date[2] . '-' . $date[1] . '-' . $date[0];
                            $date = str_replace(' ', '', $date);
                            return $date;
                    })->sort(),
    
                    TD::make('', 'Вид работ')
                        ->width('400')
                        ->render(function (Job $job) {
                            $speciality = User::select('speciality')->where('id', $job->worker_id)->get()->toArray();
                            $speciality = $speciality[0]['speciality'];
    
                            return Str::limit($speciality);
                    }),
    
                    TD::make('', 'Исполнитель')
                        ->width('400')
                        ->render(function (Job $job) {
                            $worker = User::select('surname')->where('id', $job->worker_id)->get()->toArray();
                            $worker = $worker[0]['surname'];
    
                            return Str::limit($worker);
                    }),
    
                    TD::make('status', 'Статус')
                        ->width('400')
                        ->render(function (Job $job) {
                            return Str::limit($job->status);
                    })->sort(),
    
                    TD::make('', 'Комментарий')
                        ->width('400')
                        ->render(function (Job $job) {
                            return Str::limit($job->reject_reason);
                    }),
    
                    TD::make('', '')
                        //->width('200')
                        ->render(function (Job $job) {
                            return Group::make([
                                Link::make('Перейти')
                                    ->type(Color::DARK())
                                    ->route('platform.projectJobUpdate', ['job_id' => $job->id])
                            ])->autoWidth();
                        }),
                ])->title('Работы на объектах'),

                Layout::table('workerMessages', [
                    TD::make('title', 'Тема'),
                    TD::make('content', 'Сообщение'),
                    TD::make('first_name', 'Имя'),
                    TD::make('surname', 'Фамилия'),
                    TD::make('patronymic', 'Отчество')
                ])->title('Сообщения от работников')
        ];
    }
}
