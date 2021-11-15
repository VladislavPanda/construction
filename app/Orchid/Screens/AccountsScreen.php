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

class AccountsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список аккаунтов';

    public $permission = 'platform.accounts';

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

            'drivers' => User::whereHas('roles', function ($query) {
                $query->where('slug', 'driver');
            })->paginate(),

            'foremen' => User::whereHas('roles', function ($query) {
                $query->where('slug', 'foreman');
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
            Layout::table('workers', [
                TD::make('', 'Имя')
                    ->width('400')
                    ->render(function (User $user) {
                        return Str::limit($user->name);
                }),

                TD::make('', 'Email')
                    ->width('400')
                    ->render(function (User $user) {
                        return Str::limit($user->email);
                }),

                TD::make('', 'Дата создания аккаунта')
                    ->width('400')
                    ->render(function (User $user) {
                        return Str::limit($user->created_at);
                }),

                TD::make('addInfo', '')
                    //->width('200')
                    ->render(function (User $user) {
                        return Group::make([
                            Button::make('Добавить сведения')
                                    ->method('addWorkerInfo')
                                    ->type(Color::PRIMARY())
                                    ->parameters([
                                        'id' => $user->id,
                                    ]),
                        ])->autoWidth();
                    }),
            ])->title('Сотрудники'),

            Layout::table('drivers', [
                TD::make('', 'Имя')
                    ->width('400')
                    ->render(function (User $user) {
                        return Str::limit($user->name);
                }),

                TD::make('', 'Email')
                    ->width('400')
                    ->render(function (User $user) {
                        return Str::limit($user->email);
                }),

                TD::make('', 'Дата создания аккаунта')
                    ->width('400')
                    ->render(function (User $user) {
                        return Str::limit($user->created_at);
                }),

                TD::make('addInfo', '')
                    //->width('200')
                    ->render(function (User $user) {
                        return Group::make([
                            Button::make('Добавить сведения')
                                    ->method('addDriverInfo')
                                    ->type(Color::PRIMARY())
                                    ->parameters([
                                        'id' => $user->id,
                                    ]),
                        ])->autoWidth();
                    }),
            ])->title('Водители'),

            Layout::table('foremen', [
                TD::make('', 'Имя')
                    ->width('400')
                    ->render(function (User $user) {
                        return Str::limit($user->name);
                }),

                TD::make('', 'Email')
                    ->width('400')
                    ->render(function (User $user) {
                        return Str::limit($user->email);
                }),

                TD::make('', 'Дата создания аккаунта')
                    ->width('400')
                    ->render(function (User $user) {
                        return Str::limit($user->created_at);
                }),

                TD::make('addInfo', '')
                    //->width('200')
                    ->render(function (User $user) {
                        return Group::make([
                            Button::make('Добавить сведения')
                                    ->method('addForemanInfo')
                                    ->type(Color::PRIMARY())
                                    ->parameters([
                                        'id' => $user->id,
                                    ]),
                        ])->autoWidth();
                    }),
            ])->title('Прорабы'),
        ];
    }

    public function addWorkerInfo(Request $request){
        $userId = $request->get('id');

        return redirect()->route('platform.workerInfoAdd', ['userId' => $userId]);        
    }

    public function addDriverInfo(Request $request){
        $userId = $request->get('id');
    
        dd($userId);
        //return redirect()->route('platform.workerInfoAdd', ['userId' => $userId]);        
    }

    public function addForemanInfo(Request $request){
        $userId = $request->get('id');
    
        dd($userId);
        //return redirect()->route('platform.workerInfoAdd', ['userId' => $userId]);        
    }
}
