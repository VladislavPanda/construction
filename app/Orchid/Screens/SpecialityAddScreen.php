<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Alert;
use App\Http\Controllers\SpecialityController;

class SpecialityAddScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Добавить специальность';

    public $permission = 'platform.specialityAdd';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [];
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
                    Input::make('speciality')
                        ->title('Специальность:')
                        ->required(),

                    Button::make('Добавить')
                        ->method('submitSpeciality')
                        ->type(Color::DEFAULT()),
                ])
            ])
        ];
    }

    public function submitSpeciality(Request $request){
        $specialityData = [];
        $specialityData['title'] = $request->get('speciality');

        $controllerObject = new SpecialityController();
        $result = $controllerObject->store($specialityData);

        if($result === true) Alert::warning('Специальность была успешно добавлена');
    }
}
