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
use App\Http\Controllers\ProjectController;

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
        $controller = new ProjectController();
        $controller->getMyProject();

        return [

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
            Layout::columns([
                Layout::view('myProject', ['project' => 'project']),
            ]),

            Layout::table('jobs', [
                TD::make('', 'Описание')
                    ->width('400')
                    ->render(function (Job $job) {
                        return Str::limit($job->description);
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
                            Button::make('Редактировать')
                                    ->method('update')
                                    ->type(Color::PRIMARY())
                                    //->class('shortBtn')
                                    ->parameters([
                                        'id' => $job->id,
                                        //'pageId' => self::$page
                                    ]),
                        ])->autoWidth();
                    }),
            ])
        ];
    }
}
