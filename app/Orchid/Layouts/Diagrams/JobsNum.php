<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Diagrams;

use Orchid\Screen\Layouts\Chart;

class JobsNum extends Chart
{
    /**
     * @var string
     */
    protected $title = 'Статусы работ';

    /**
     * @var int
     */
    protected $height = 350;

    /**
     * Available options:
     * 'bar', 'line',
     * 'pie', 'percentage'.
     *
     * @var string
     */
    protected $type = 'pie';

    /**
     * @var string
     */
    protected $target = 'jobsNum';
}
