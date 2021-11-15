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
use App\Models\User;
use App\Http\Controllers\WorkerController;

class WorkersScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Сотрудники';

    public $permission = 'platform.workers'; 

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'workers' => User::whereHas('roles', function ($query) {
                $query->where('slug', 'worker');
            })->paginate(),
            /*'drivers' => Worker::where('speciality', 'Водитель')->paginate(),
            'foremen' => Worker::where('speciality', 'Прораб')->paginate()*/
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
            Layout::table('workers', [
                TD::make('', 'Имя')
                    ->width('400')
                    ->render(function (User $user) {
                        return Str::limit($user->first_name);
                }),

                TD::make('', 'Фамилия')
                    ->width('400')
                    ->render(function (User $user) {
                        return Str::limit($user->surname);
                }),

                TD::make('', 'Отчество')
                    ->width('400')
                    ->render(function (User $user) {
                        return Str::limit($user->patronymic);
                }),

                TD::make('', 'Специальность')
                    ->width('400')
                    ->render(function (User $user) {
                        return Str::limit($user->speciality);
                }),

                TD::make('', 'Телефон')
                    ->width('400')
                    ->render(function (User $user) {
                        return Str::limit($user->phone);
                }),

                TD::make('', 'Статус')
                    ->width('400')
                    ->render(function (User $user) {
                        return Str::limit($user->status);
                }),

                TD::make('update', '')
                    //->width('200')
                    ->render(function (User $user) {
                        return Group::make([
                            Button::make('Редактировать')
                                    ->method('update')
                                    ->type(Color::PRIMARY())
                                    ->parameters([
                                        'worker_id' => $user->id,
                                    ]),
                        ])->autoWidth();
                    }),
            ])
        ];
    }
}
