<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Orchid\Screen\Repository;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Status;
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

    private static $driverId;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {   
        $table = [];
        $drivers = [];
        $statuses = [];

        // Получаем пользователей с ролью водитель
        $drivers = User::whereHas('roles', function ($query) {
            $query->where('slug', 'driver');
        })->get()->toArray();

        // Получаем статусы водителей
        $statuses = Status::select('title')->get()->toArray();
        
        // Совмещаем водителей и их статусы в единый массив
        foreach($drivers as $key => &$value){
            $value['status'] = $statuses[$key]['title'];
        }

        // Заполнение итогового массива данными через репозиторий
        for($i = 0; $i < sizeof($drivers); $i++){
            $table[] = new Repository([
                                       'id' => $drivers[$i]['id'],
                                       'name' => $drivers[$i]['name'],
                                       'email' => $drivers[$i]['email'],
                                       'first_name' => $drivers[$i]['first_name'],
                                       'surname' => $drivers[$i]['surname'],
                                       'patronymic' => $drivers[$i]['patronymic'],
                                       'phone' => $drivers[$i]['phone'],
                                       'status' => $drivers[$i]['status'],
                                       ]);                           
        }

        // Формирование пагинатора
        $perPage = 30;
        $path = '';
        $page = Paginator::resolveCurrentPage();
        $currentPage = $page - 1;
        $currentPage < 0 ? $currentPage = 0 : '';
        $collection = new Collection($table);

        $currentPageSearchResults = $collection->slice($currentPage * $perPage, $perPage)->all();
        $paginator = new Paginator($currentPageSearchResults, count($collection), $perPage);
        $request = new Request;
        $url = $request->path();
        $path ? $path : $url;
        $paginator->setPath($path);

        return [
            'table' => $paginator,
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
            Layout::table('table', [
                TD::make('name', 'Логин'),
                    //->width('250'),
                TD::make('email', 'Email'),
                    //->width('350'),
                TD::make('first_name', 'Имя'),
                    //->width('200'),
                TD::make('surname', 'Фамилия'),
                    //->width('200'),
                TD::make('patronymic', 'Отчество'),
                    //->width('200'),
                TD::make('phone', 'Телефон'),
                    //->width('200'),
                TD::make('status', 'Статус'),
                    //->width('200'),

                TD::make('setTask', '')
                    //->width('200')
                    ->render(function (Repository $model) {
                        return Group::make([
                            Button::make('Назначить задачу')
                                    ->method('setTask')
                                    ->class('tableBtn')
                                    //->type(Color::PRIMARY())
                                    ->parameters([
                                        'driver_id' => $model->get('id'),
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
