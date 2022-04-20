<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
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
        $jobs = [];
        $diagramsService = new DiagramsService();
        $jobs = $diagramsService->jobsChart();

        return [
            'charts' => [
                [
                    'name'   => 'Jobs',
                    'values' => $jobs,
                    'labels' => ['В работе', 'Выполнено', 'Не выполнено', 'Отменено'],
                ],
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
                Jobs::class,
            ]),
        ];
    }
}
