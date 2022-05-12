<?php

declare(strict_types=1);

use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

use App\Orchid\Screens\SpecialityAddScreen;
use App\Orchid\Screens\SpecialitiesViewScreen;
use App\Orchid\Screens\SpecialityUpdateScreen;
use App\Orchid\Screens\SalaryViewScreen;
use App\Orchid\Screens\TasksViewScreen;
use App\Orchid\Screens\MyDriverTasksScreen;
use App\Orchid\Screens\DriverTaskAddScreen;
use App\Orchid\Screens\DriverTasksScreen;
use App\Orchid\Screens\DriverTaskUpdateScreen;
use App\Orchid\Screens\ProjectAddScreen;
use App\Orchid\Screens\ProjectsScreen;
use App\Orchid\Screens\ProjectJobsScreen;
//use App\Orchid\Screens\ProjectForemanSetScreen;
use App\Orchid\Screens\ProjectUpdateScreen;
use App\Orchid\Screens\ProjectJobsAddScreen;
use App\Orchid\Screens\WorkerJobsScreen;
use App\Orchid\Screens\MyProjectScreen;
use App\Orchid\Screens\JobUpdateScreen;
use App\Orchid\Screens\ForemanTasksScreen;
use App\Orchid\Screens\WorkersScreen;
use App\Orchid\Screens\DriversScreen;
use App\Orchid\Screens\ForemenScreen;
use App\Orchid\Screens\MessagesScreen;
use App\Orchid\Screens\WorkerMessagesScreen;
use App\Orchid\Screens\WorkerMessageAddScreen;
use App\Orchid\Screens\BidsScreen;
use App\Orchid\Screens\DiagramsScreen;
use App\Orchid\Screens\NotesScreen;
use App\Orchid\Screens\SalariesScreen;
use App\Orchid\Screens\BudgetBidsScreen;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Profile'), route('platform.profile'));
    });

// Platform > System > Users
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(function (Trail $trail, $user) {
        return $trail
            ->parent('platform.systems.users')
            ->push(__('User'), route('platform.systems.users.edit', $user));
    });

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.users')
            ->push(__('Create'), route('platform.systems.users.create'));
    });

// Platform > System > Users > User
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Users'), route('platform.systems.users'));
    });

// Platform > System > Roles > Role
Route::screen('roles/{roles}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(function (Trail $trail, $role) {
        return $trail
            ->parent('platform.systems.roles')
            ->push(__('Role'), route('platform.systems.roles.edit', $role));
    });

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.roles')
            ->push(__('Create'), route('platform.systems.roles.create'));
    });

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Roles'), route('platform.systems.roles'));
    });

// Example...
Route::screen('example', ExampleScreen::class)
    ->name('platform.example')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push('Example screen');
    });

Route::screen('example-fields', ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('example-layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('example-charts', ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('example-editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('example-cards', ExampleCardsScreen::class)->name('platform.example.cards');
Route::screen('example-advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');

// Роуты платформы

// Роуты страниц админа
Route::screen('specialityAdd', SpecialityAddScreen::class)->name('platform.specialityAdd');
Route::screen('specialitiesView', SpecialitiesViewScreen::class)->name('platform.specialitiesView');
Route::screen('specialityUpdate', SpecialityUpdateScreen::class)->name('platform.specialityUpdate');
Route::screen('salaries', SalariesScreen::class)->name('platform.salaries');

// Роуты страниц менеджера
Route::screen('workers', WorkersScreen::class)->name('platform.workers');
Route::screen('drivers', DriversScreen::class)->name('platform.drivers');
Route::screen('foremen', ForemenScreen::class)->name('platform.foremen');
Route::screen('driverTaskAdd', DriverTaskAddScreen::class)->name('platform.driverTaskAdd');
Route::screen('driverTasks', DriverTasksScreen::class)->name('platform.driverTasks');
Route::screen('driverTaskUpdate', DriverTaskUpdateScreen::class)->name('platform.driverTaskUpdate');
Route::screen('projectAdd', ProjectAddScreen::class)->name('platform.projectAdd');
Route::screen('projects', ProjectsScreen::class)->name('platform.projects');
Route::screen('projectJobs', ProjectJobsScreen::class)->name('platform.projectJobs');
//Route::screen('projectForemanSet', ProjectForemanSetScreen::class)->name('platform.projectForemanSet');
Route::screen('projectUpdate', ProjectUpdateScreen::class)->name('platform.projectUpdate');
Route::screen('projectJobsAdd', ProjectJobsAddScreen::class)->name('platform.projectJobsAdd');
Route::screen('projectJobUpdate', JobUpdateScreen::class)->name('platform.projectJobUpdate');
Route::screen('messages', MessagesScreen::class)->name('platform.messages');
Route::screen('bids', BidsScreen::class)->name('platform.bids');
Route::screen('diagrams', DiagramsScreen::class)->name('platform.diagrams');

// Роуты страниц сотрудника
Route::screen('workerJobs', WorkerJobsScreen::class)->name('platform.workerJobs');
Route::screen('workerMessages', WorkerMessagesScreen::class)->name('platform.workerMessages');
Route::screen('workerMessageAdd', WorkerMessageAddScreen::class)->name('platform.workerMessageAdd');

// Роуты страниц водителя
Route::screen('myDriverTasks', MyDriverTasksScreen::class)->name('platform.myDriverTasks');

// Роуты страниц прораба
Route::screen('myProject', MyProjectScreen::class)->name('platform.myProject');
Route::screen('notes', NotesScreen::class)->name('platform.notes');
Route::screen('budgetBids', BudgetBidsScreen::class)->name('platform.budgetBids');

/*
Route::screen('addSalary', SalaryAddScreen::class)->name('platform.addSalary');
Route::screen('driverInfoAdd', DriverInfoAddScreen::class)->name('platform.addDriverInfo');
Route::screen('addDriverTask', DriverTaskAddScreen::class)->name('platform.addSalary');

*/
//Route::screen('idea', 'Idea::class','platform.screens.idea');
