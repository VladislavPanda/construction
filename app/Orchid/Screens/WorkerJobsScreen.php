<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use App\Services\AuthHandler;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Alert;
use Illuminate\Support\Str;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use App\Http\Controllers\JobController;
use App\Models\Job;
use App\Models\User;
use App\Models\Project;

class WorkerJobsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Мои работы';
    public $permission = 'platform.workerJobs';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $workerId = AuthHandler::getCurrentUser();

        return [
            'jobs' => Job::filters()->where('worker_id', $workerId)->paginate()
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
            Layout::table('jobs', [
                TD::make('', 'Адрес')
                    ->width('400')
                    ->render(function (Job $job) {
                        $address = Project::select('address')->where('id', $job->project_id)->get()->toArray();
                        $address = $address[0]['address'];
                        //return $address;//Str::limit($job->description);
                        return view('tableData', ['data' => $address]);
                }),

                TD::make('', 'Описание')
                    ->width('400')
                    ->render(function (Job $job) {
                        //return Str::limit($job->description);
                        return view('tableData', ['data' => $job->description]);
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
                    ->render(function (Job $job) {
                        return Group::make([
                            ModalToggle::make('Запросить сумму')
                                //->type(Color::PRIMARY())
                                ->class('shortBtn')
                                ->modal('budget_modal')
                                ->parameters([
                                    'id' => $job->id,
                                ])
                                ->method('budget')
                        ])->autoWidth();
                    }),
            ]),

            Layout::modal('budget_modal', Layout::rows([
                TextArea::make('budget_bid_description')
                        ->title('Описание запроса:')
                        ->required()
                        ->rows(6),

                Input::make('sum')
                        ->title('Сумма')
                        ->type('number')
                        ->required()
                        ->min(1),
            ]))->title('Введите данные запроса на сумму')->applyButton('Отправить')
            ->closeButton('Закрыть'),
        ];
    }

    public function budget(Request $request){
        $budgetBid = $request->except(['_token']);
    
        $controller = new JobController();
        $flag = $controller->putBudgetBid($budgetBid);

        if($flag === false) Alert::warning('Запрошенная сумма превышает бюджет'); 
        else Alert::warning('Запрос был отправлен');
    }
}
