<?php


namespace App\Http\Filters;


use Illuminate\Database\Eloquent\Builder;

class AlbumFilter extends AbstractFilter
{
    public const PERFORMER = 'performer_id';


    protected function getCallbacks(): array
    {
        return [
            self::PERFORMER => [$this, 'performer_id'],
        ];
    }

    public function performer_id(Builder $builder, string $value)
    {
        $builder->where('performer_id', $value);
    }
}
