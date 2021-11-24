<?php

namespace App\Orchid\Layouts;

use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;
use App\Orchid\Filters\PhoneFilter;

class PhoneSelection extends Selection
{
    /**
     * @return Filter[]
     */
    public function filters(): array
    {
        return [
            PhoneFilter::class
        ];
    }
}
