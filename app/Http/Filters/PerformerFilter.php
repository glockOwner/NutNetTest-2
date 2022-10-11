<?php


namespace App\Http\Filters;


use Illuminate\Database\Eloquent\Builder;

class PerformerFilter extends AbstractFilter
{
    public const NAME = 'name';


    protected function getCallbacks(): array
    {
        return [
            self::NAME => [$this, 'name'],
        ];
    }

    public function name(Builder $builder, string $value)
    {
        $builder->where('name', 'like', "%{$value}%");
    }
}
