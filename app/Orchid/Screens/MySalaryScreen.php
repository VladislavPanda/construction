<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Group;
use App\Services\SalaryService;

class MySalaryScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Контроль заработной платы';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $salaryData = [];

        $service = new SalaryService();
        $salaryData = $service->getSalary();

        return [
            'salaryData' => $salaryData
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
                Layout::view('salaryInfo', ['salaryData' => 'salaryData']),
            ]),
        ];
    }
}
