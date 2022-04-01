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
use App\Models\Bid;

class BidsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Заявки';
    public $permission = 'platform.bids';
    private const CATEGORIES = [
        'construction' => 'Строительство',
        'maintenance' => 'Обслуживание объектов',
        'project' => 'Проектирование'
    ];

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'bids' => Bid::paginate()
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
            Layout::table('bids', [
                TD::make('name', 'Имя заявителя')
                    //->width('400')
                    ->render(function (Bid $bid) {
                        return Str::limit($bid->name);
                        //return view('tableData', ['data' => $task->address]);
                }),

                TD::make('', 'Телефон')
                    //->width('400')
                    ->render(function (Bid $bid) {
                        return Str::limit($bid->phone);
                }),

                TD::make('', 'Категория работ')
                    //->width('400')
                    ->render(function (Bid $bid) {
                        $category = BidsScreen::CATEGORIES[$bid->category];

                        return Str::limit($category);
                        //return view('tableData', ['data' => $task->description]);
                }),

                TD::make('date', 'Дата')
                    //->width('400')
                    ->render(function (Bid $bid) {
                        //return Str::limit($task->end_date);
                        $date = str_replace('00:00:00', '', $bid->date);
                        $date = explode('-', $date);
                        $date = $date[2] . '-' . $date[1] . '-' . $date[0];
                        $date = str_replace(' ', '', $date);
                        return $date;
                })->sort(),

                TD::make('message', 'Сообщение')
                    //->width('400')
                    ->render(function (Bid $bid) {
                        return Str::limit($bid->message);
                })->sort(),

                /*TD::make('', '')
                    //->width('200')
                    ->render(function (Bid $bid) {
                        return Group::make([
                            Button::make('Редактировать')
                                    ->method('update')
                                    ->type(Color::PRIMARY())
                                    //->class('longDocumentBtn')
                                    ->parameters([
                                        'id' => $task->id,
                                    ]),
                        ])->autoWidth();
                    }),*/
            ]),
        ];
    }
}
