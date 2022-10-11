<?php

namespace App\Repositories;

use App\Models\Album;
use App\Models\Performer;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PerformerRepository implements RepositoryInterface
{

    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return Performer::all();
    }

    public function getById(string $id): Album|Performer|null
    {
        return Performer::find($id);
    }

    public function getByName(string $name): Album|Performer|null
    {
        return Performer::where('name', $name)->first();
    }

    public function getWithFilter($filter): ?LengthAwarePaginator
    {
        return Performer::filter($filter)->paginate(5);
    }
}
