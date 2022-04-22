<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Orchid\Support\Facades\Alert;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Matrix;
use App\Http\Controllers\SalaryController;

class SalariesScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Оклады';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $salaries = [];

        $controller = new SalaryController();
        $salaries = $controller->getSpecialities();

        return [
            'salaries' => $salaries
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
                Layout::rows([
                    Button::make('Сохранить')
                        ->method('update')
                        ->type(Color::DEFAULT())
                ])
             ]),

            Layout::columns([
                Layout::rows([
                    Matrix::make('salaries')
                        ->columns([
                            'Специальность',
                            'Сумма оклада'
                        ])
                        ->fields([
                            'Специальность' => Input::make()->readonly(),
                            'Сумма оклада' => Input::make()->type('number')->min(0),
                        ])
                        ->required(),
                ])
            ]),
        ];
    }

    public function update(Request $request){
        
    }
}
