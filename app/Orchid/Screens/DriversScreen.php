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
use App\Http\Controllers\DriverController;

class DriversScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Водители';

    public $permission = 'platform.drivers'; 
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {   
        return [
            'drivers' => User::whereHas('roles', function ($query) {
                $query->where('slug', 'driver');
            })->paginate(),
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
            Layout::table('drivers', [
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
                            Button::make('Назначить задачу')
                                    ->method('setTask')
                                    ->class('tableBtn')
                                    //->type(Color::PRIMARY())
                                    ->parameters([
                                        'driver_id' => $user->id,
                                    ]),
                        ])->autoWidth();
                    }),
            ])
        ];
    }

    public function setTask(Request $request){
        $userId = $request->get('driver_id');

        return redirect()->route('platform.driverTaskAdd', ['driver_id' => $userId]);
    }
}
