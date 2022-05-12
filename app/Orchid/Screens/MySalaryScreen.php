<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
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
        return [];
    }
}
