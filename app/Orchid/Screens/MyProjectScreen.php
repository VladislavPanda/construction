<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\TD;
use Illuminate\Support\Str;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\TextArea;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\JobController;
use App\Services\AuthHandler;

use App\Models\Project;
use App\Models\Job;
use App\Models\User;

class MyProjectScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Текущий объект';
    public $permission = 'platform.myProject';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $foremanId = AuthHandler::getCurrentUser();

        $project = Project::select('id')->where('user_id', $foremanId)->get()->toArray();
        if(isset($project[0])){ 
            $projectId = $project[0]['id'];

            return [
                'projectInfo' => Project::where('user_id', $foremanId)->where('status', 'В работе')->get()->toArray(),
                'jobs' => Job::filters()->where('project_id', $projectId)->paginate()
            ];
        }else{
            return [
                'projectInfo' => [],
                'jobs' => []
            ];
        }
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
                Layout::view('myProject', ['projectInfo' => 'projectInfo']),
            ]),

            Layout::table('jobs', [
                TD::make('', 'Описание')
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

                TD::make('', 'Статус')
                    ->width('400')
                    ->render(function (Job $job) {
                        return Str::limit($job->status);
                }),

                TD::make('', 'Комментарий')
                    ->width('400')
                    ->render(function (Job $job) {
                        return Str::limit($job->reject_reason);
                }),

                TD::make('', '')
                    //->width('200')
                    ->render(function (Job $job) {
                        return Group::make([
                            /*Button::make('Редактировать')
                                    ->method('update')
                                    ->type(Color::PRIMARY())
                                    //->class('shortBtn')
                                    ->parameters([
                                        'id' => $job->id,
                                        //'pageId' => self::$page
                                    ]),*/
                            Button::make('Выполнено')
                                ->method('setDone')
                                //->type(Color::PRIMARY())
                                ->class('shortBtn')
                                ->parameters([
                                    'id' => $job->id,
                                    //'pageId' => self::$page
                                ]),        

                            ModalToggle::make('Отклонить')
                                //->type(Color::PRIMARY())
                                ->class('shortBtn')
                                ->modal('reject_reason_modal')
                                ->parameters([
                                    'id' => $job->id,
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

    public function setDone(Request $request){
        $flag = false;
        $jobId = $request->get('id');

        $controller = new JobController();
        $flag = $controller->setDone($jobId);

        if($flag === true) Alert::warning('Задача отмечена как выполненная');
    }

    public function reject(Request $request){
        $flag = false;
        $jobId = $request->get('id');
        $rejectReason = $request->get('reject_reason');

        $controller = new JobController();
        $flag = $controller->setReject($jobId, $rejectReason);   
    }
}
