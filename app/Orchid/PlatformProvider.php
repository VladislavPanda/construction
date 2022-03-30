<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;
use App\Services\MessagesService;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * @return Menu[]
     */
    public function registerMainMenu(): array
    {
        $messagesNum = MessagesService::getMessagesNum();

        return [
            Menu::make('Добавить специальность')
                ->icon('plus')
                ->route('platform.specialityAdd')
                ->permission('platform.specialityAdd'),

            Menu::make('Список специальностей')
                ->icon('folder')
                ->route('platform.specialitiesView')
                ->permission('platform.specialitiesView'),

            Menu::make('Сотрудники')
                ->icon('user-following')
                ->route('platform.workers')
                ->permission('platform.workers'),

            Menu::make('Водители')
                ->icon('basket')
                ->route('platform.drivers')
                ->permission('platform.drivers'),

            Menu::make('Прорабы')
                ->icon('briefcase')
                ->route('platform.foremen')
                ->permission('platform.foremen'),

            Menu::make('Оповещения (' . $messagesNum . ')')
                ->icon('envelope')
                ->route('platform.messages')
                ->permission('platform.messages'),

            /*Menu::make('Добавить задачу водителям')
                ->icon('plus')
                ->route('platform.driver')
                ->permission('platform.workerAdd'),*/

            /*Menu::make('Просмотреть зарплату')
                ->icon('money')
                ->route('platform.salaryView')
                ->permission('platform.salaryView'),*/

            Menu::make('Мои задачи')
                ->icon('task')
                ->route('platform.myDriverTasks')
                ->permission('platform.myDriverTasks'),

            Menu::make('Текущий объект')
                ->icon('building')
                ->route('platform.myProject')
                ->permission('platform.myProject'),

            Menu::make('Добавить объект')
                ->icon('plus')
                ->route('platform.projectAdd')
                ->permission('platform.projectAdd'),

            Menu::make('Объекты')
                ->icon('building')
                ->route('platform.projects')
                ->permission('platform.projects'),

            Menu::make('Мои работы')
                ->icon('task')
                ->route('platform.workerJobs')
                ->permission('platform.workerJobs'),

            Menu::make('Сообщения менеджерам')
                ->icon('envelope-letter')
                ->route('platform.workerMessages')
                ->permission('platform.workerMessages'),

            /*Menu::make('Example screen')
                ->icon('monitor')
                ->route('platform.example')
                ->title('Navigation')
                ->badge(function () {
                    return 6;
                }),

            Menu::make('Dropdown menu')
                ->icon('code')
                ->list([
                    Menu::make('Sub element item 1')->icon('bag'),
                    Menu::make('Sub element item 2')->icon('heart'),
                ]),

            Menu::make('Basic Elements')
                ->title('Form controls')
                ->icon('note')
                ->route('platform.example.fields'),

            Menu::make('Advanced Elements')
                ->icon('briefcase')
                ->route('platform.example.advanced'),

            Menu::make('Text Editors')
                ->icon('list')
                ->route('platform.example.editors'),

            Menu::make('Overview layouts')
                ->title('Layouts')
                ->icon('layers')
                ->route('platform.example.layouts'),

            Menu::make('Chart tools')
                ->icon('bar-chart')
                ->route('platform.example.charts'),

            Menu::make('Cards')
                ->icon('grid')
                ->route('platform.example.cards')
                ->divider(),

            Menu::make('Documentation')
                ->title('Docs')
                ->icon('docs')
                ->url('https://orchid.software/en/docs'),

            Menu::make('Changelog')

                ->icon('shuffle')
                ->url('https://github.com/orchidsoftware/platform/blob/master/CHANGELOG.md')
                ->target('_blank')
                ->badge(function () {
                    return Dashboard::version();
                }, Color::DARK()),*/

            
            Menu::make(__('Пользователи'))
                ->icon('user')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Права доступа')),

            /*Menu::make(__('Роли'))
                ->icon('lock')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles'),*/
        ];
    }

    /**
     * @return Menu[]
     */
    public function registerProfileMenu(): array
    {
        return [
            Menu::make('Профиль')
                ->route('platform.profile')
                ->icon('user'),
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function registerPermissions(): array
    {
        // роли: админ, менеджер, водитель, сотрудник
        // + админ: может создавать и удалять аккаунты пользователей со всеми ролями (м,в,с), добавлять специальности
        // менеджер: может заполнять инфу о созданных сотрудниках (аккаунты создает админ), поля: ФИО, специальность (выпадайка - список созданных специальностей), телефон. также может давать задачи водителям
        // сотрудник: просмотр зп
        // водитель: просмотр заказов (кнопка обработал или нет) если нет, то всплывайка с причиной почему не обработал, 
        // поля задания: адрес, название, описание, время (промежуток), что нужно иметь, контакт

        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),

            ItemPermission::group(__('Администратор'))
                ->addPermission('platform.specialityAdd', __('Добавить специальность'))
                ->addPermission('platform.specialitiesView', __('Список специальностей'))
                ->addPermission('platform.specialityUpdate', __('Обновить специальность')),

            ItemPermission::group(__('Менеджер'))
                ->addPermission('platform.workers', __('Список сотрудников'))
                ->addPermission('platform.drivers', __('Список водителей'))
                ->addPermission('platform.foremen', __('Список прорабов'))
                ->addPermission('platform.driverTaskAdd', __('Назначить задачу водителю'))
                ->addPermission('platform.driverTasks', __('Задачи водителя'))
                ->addPermission('platform.driverTaskUpdate', __('Редактировать задачу водителя'))
                ->addPermission('platform.projectAdd', __('Добавить объект'))
                ->addPermission('platform.projects', __('Объекты'))
                ->addPermission('platform.projectJobs', __('Работы на объекте'))
                ->addPermission('platform.projectForemanSet', __('Назначить прораба'))
                ->addPermission('platform.projectUpdate', __('Редактировать объект'))
                ->addPermission('platform.projectJobsAdd', __('Добавить работы'))
                ->addPermission('platform.projectJobUpdate', __('Редактировать работу'))
                ->addPermission('platform.messages', __('Оповещения')),
            
            ItemPermission::group(__('Сотрудник'))
                ->addPermission('platform.workerJobs', __('Мои работы'))
                ->addPermission('platform.workerMessages', __('Сообщения менеджеру'))
                ->addPermission('platform.workerMessageAdd', __('Написать сообщение')),

            ItemPermission::group(__('Водитель'))
                ->addPermission('platform.myDriverTasks', __('Задачи')),

            ItemPermission::group(__('Прораб'))
                ->addPermission('platform.myProject', __('Текущий объект'))
        ];
    }
}
