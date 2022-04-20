<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\Diagrams\JobsNum;
use App\Orchid\Layouts\Diagrams\Jobs;
use App\Services\DiagramsService;

class DiagramsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Диаграммы';

    public $permission = 'platform.diagrams';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $jobsNum = [];
        $diagramsService = new DiagramsService();
        $jobsNum = $diagramsService->jobsChart();
        $jobs = $diagramsService->jobs();

        return [
            'jobsNum' => [
                [
                    'name'   => 'JobsNum',
                    'values' => $jobsNum,
                    'labels' => ['В работе', 'Выполнено', 'Не выполнено', 'Отменено'],
                ],
            ],

            'jobs' => [
                [
                    'name'   => 'Jobs',
                    'values' => $jobs,
                    'labels' => ['Архитектор', 'Каменщик', 'Монтажник', 'Сантехник', 'Водитель крана'],
                ]
            ],
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
                JobsNum::class,
            ]),

            Layout::columns([
                Jobs::class
            ])
        ];
    }
}
