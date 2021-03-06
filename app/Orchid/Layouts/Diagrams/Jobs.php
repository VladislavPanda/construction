<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Diagrams;

use Orchid\Screen\Layouts\Chart;

class Jobs extends Chart
{
    /**
     * @var string
     */
    protected $title = 'Виды работ';

    /**
     * @var int
     */
    protected $height = 160;

    /**
     * Available options:
     * 'bar', 'line',
     * 'pie', 'percentage'.
     *
     * @var string
     */
    protected $type = 'percentage';

    /**
     * @var string
     */
    protected $target = 'jobs';
}
