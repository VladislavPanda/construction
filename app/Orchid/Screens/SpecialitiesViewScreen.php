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
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use App\Models\Speciality;
use App\Http\Controllers\SpecialityController;
use App\Http\Controllers\SalaryController;

class SpecialitiesViewScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список специальностей';

    public $permission = 'platform.specialitiesView';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'specialities' => Speciality::paginate(),
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
            Layout::table('specialities', [
                TD::make('', 'Название')
                    ->width('400')
                    ->render(function (Speciality $speciality) {
                        return Str::limit($speciality->title);
                }),

                TD::make('', 'Оклад')
                ->width('400')
                ->render(function (Speciality $speciality) {
                    return Str::limit($speciality->salary);
                }),

                TD::make('update', '')
                    //->width('200')
                    ->render(function (Speciality $speciality) {
                        return Group::make([
                            ModalToggle::make('Задать оклад')
                                ->type(Color::PRIMARY())
                                ->modal('salary_modal')
                                ->parameters([
                                    'id' => $speciality->id,
                                ])
                                ->method('setSalary'), 
                        ])->autoWidth();
                    }),

                    TD::make('delete', '')
                    //->width('200')
                    ->render(function (Speciality $speciality) {
                        return Group::make([
                            Button::make('Редактировать')
                                    ->method('updateSpeciality')
                                    ->type(Color::PRIMARY())
                                    ->parameters([
                                        'id' => $speciality->id,
                                    ]),
                        ])->autoWidth();
                    }),

                TD::make('delete', '')
                    //->width('200')
                    ->render(function (Speciality $speciality) {
                        return Group::make([
                            Button::make('Удалить')
                                    ->method('deleteSpeciality')
                                    ->type(Color::PRIMARY())
                                    ->parameters([
                                        'id' => $speciality->id,
                                    ]),
                        ])->autoWidth();
                    }),
            ]),

            Layout::modal('salary_modal', Layout::rows([
                Input::make('salary')
                            ->type('number')
                            ->min(1)
            ]))->title('Введите оклад')->applyButton('Отправить')
            ->closeButton('Закрыть'),
        ];
    }

    public function updateSpeciality(Request $request){
        $id = $request->get('id');
        
        return redirect()->route('platform.specialityUpdate', ['id' => $id]);
    }

    public function deleteSpeciality(Request $request){
        $id = $request->get('id');
        
        $controllerObject = new SpecialityController();
        $controllerObject->delete($id);
    }

    public function setSalary(Request $request){
        $salaryData = $request->except(['_token']);

        $controller = new SalaryController();
        $controller->update($salaryData);
    }
}
