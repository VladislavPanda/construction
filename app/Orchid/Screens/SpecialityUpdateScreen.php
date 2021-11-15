<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Select;
use App\Http\Controllers\SpecialityController;
use App\Models\Speciality;

class SpecialityUpdateScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Редактировать специальность';
    private static $id;
    public $permission = 'platform.specialityUpdate';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $id = $_GET['id'];
        self::$id = $id;
        $currentSpecialityData = [];
        $controller = new SpecialityController();
        $currentSpecialityData = $controller->getCurrentData($id);

        return [
            'speciality' => $currentSpecialityData['title']
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
                    Input::make('speciality')
                        ->title('Новое название специальности:'),

                    Button::make('Редактировать')
                        ->method('specialityUpdate')
                        ->type(Color::DEFAULT())
                        ->parameters([
                            'id' => self::$id,
                        ]),
                ])
            ])
        ];
    }

    public function specialityUpdate(Request $request){
        $id = $request->get('id');
        $specialityUpdateData = $_POST;
        
        $controller = new SpecialityController();
        $result = $controller->update($specialityUpdateData, $id);
        if($result === true) return redirect()->route('platform.specialitiesView');
    }
}
