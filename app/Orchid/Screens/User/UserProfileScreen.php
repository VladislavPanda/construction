<?php

declare(strict_types=1);

namespace App\Orchid\Screens\User;

use App\Orchid\Layouts\User\ProfilePasswordLayout;
use App\Orchid\Layouts\User\UserEditLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Orchid\Platform\Models\User;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class UserProfileScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Мой аккаунт';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Обновите данные своей учетной записи, такие как имя, адрес электронной почты и пароль';

    /**
     * Query data.
     *
     * @param Request $request
     *
     * @return array
     */
    public function query(Request $request): array
    {
        return [
            'user' => $request->user(),
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): array
    {
        return [
            Layout::block(UserEditLayout::class)
                ->title(__('Информация профиля'))
                ->description(__("Отредактируйте информацию профиля и email"))
                ->commands(
                    Button::make(__('Сохранить'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->method('save')
                ),

            Layout::block(ProfilePasswordLayout::class)
                ->title(__('Сменить пароль'))
                ->description(__('Убедитесь, что в вашей учетной записи используется длинный случайный пароль, чтобы оставаться в безопасности'))
                ->commands(
                    Button::make(__('Ихменить пароль'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->method('changePassword')
                ),
        ];
    }

    /**
     * @param Request $request
     */
    public function save(Request $request): void
    {
        $request->validate([
            'user.name'  => 'required|string',
            'user.email' => [
                'required',
                Rule::unique(User::class, 'email')->ignore($request->user()),
            ],
        ]);

        $request->user()
            ->fill($request->get('user'))
            ->save();

        Toast::info(__('Профиль отредактирован'));
    }

    /**
     * @param Request $request
     */
    public function changePassword(Request $request): void
    {
        $request->validate([
            'old_password' => 'required|password:web',
            'password'     => 'required|confirmed',
        ]);

        tap($request->user(), function ($user) use ($request) {
            $user->password = Hash::make($request->get('password'));
        })->save();

        Toast::info(__('Пароль изменён'));
    }
}
