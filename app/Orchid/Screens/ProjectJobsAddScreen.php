<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;

class ProjectJobsAddScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Добавить работы';
    public $permission = 'platform.projectJobsAdd';
    private static $projectId;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $projectId = $_GET['project_id'];
        self::$projectId = $projectId;

        dd(self::$projectId);
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
