<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use App\Models\User;

class PhoneFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = ['phone'];

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Фильтр по номеру телефона';
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->where('phone', $this->request->get('phone'));
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return[
            Select::make('phone')
                    ->fromModel(User::class, 'phone', 'phone')
                    ->empty()
                    ->value($this->request->get('phone'))
                    ->title('Фильтр телефона')
        ];
    }
}
