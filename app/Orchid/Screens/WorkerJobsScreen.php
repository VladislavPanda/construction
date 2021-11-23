<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use App\Services\AuthHandler;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\TD;
use Illuminate\Support\Str;
use Orchid\Support\Color;
use Illuminate\Http\Request;
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

                TD::make('', 'Дата завершения')
                    ->width('400')
                    ->render(function (Job $job) {
                        return Str::limit($job->date);
                }),

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
            ])
        ];
    }
}
