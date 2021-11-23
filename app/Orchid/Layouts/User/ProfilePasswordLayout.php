<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Password;
use Orchid\Screen\Layouts\Rows;

class ProfilePasswordLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Password::make('old_password')
                ->placeholder(__('Введите текущий пароль'))
                ->title(__('Текущий пароль'))
                ->help('Это пароль, установленный в настоящий момент'),

            Password::make('password')
                ->placeholder(__('Введите новый пароль'))
                ->title(__('Новый пароль')),

            Password::make('password_confirmation')
                ->placeholder(__('Введите новый пароль'))
                ->title(__('Подтвердите пароль'))
                ->help('Хороший пароль должен содержать не менее 15 или не менее 8 символов, включая цифру и строчную букву'),
        ];
    }
}
