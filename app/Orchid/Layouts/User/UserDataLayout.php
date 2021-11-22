<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Select;
use App\Models\Speciality;
use App\Models\User;

class UserDataLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        /*$cSpec = User::select('speciality')->where('email', $userData['email'])->get()->toArray();
        $cSpec = $cSpec[0]['speciality'];*/

        return [
            Input::make('user.first_name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Имя')),
                //->placeholder(__(')),

            Input::make('user.surname')
                ->type('text')
                ->required()
                ->title(__('Фамилия')),

            Input::make('user.patronymic')
                ->type('text')
                ->required()
                ->title(__('Отчество')),

            Select::make('user.speciality')
                ->multiple('1')
                ->maximumSelectionLength(1)
                ->fromModel(Speciality::class, 'title')
                ->title('Специальность'),

            Input::make('user.phone')
                ->type('text')
                ->required()
                ->title(__('Телефон')),
        ];
    }
}