<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use App\Http\Controllers\ProjectController;

class ProjectJobsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Работы на объекте';
    public $permission = 'platform.projectJobs';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        /*$projectId = $_GET['project_id'];
        $controller = new ProjectController();
        $jobs = $controller->getProjectJobs($projectId);*/

        return [
            //'jobs' => $jobs
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
            /*Layout::columns([
                Layout::view('projectJobs', ['jobs' => 'jobs']),
            ]),*/
        ];
    }
}