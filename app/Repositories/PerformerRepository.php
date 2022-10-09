<?php

namespace App\Repositories;

use App\Models\Album;
use App\Models\Performer;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PerformerRepository implements RepositoryInterface
{

    public function getAll(): ?LengthAwarePaginator
    {
        return Performer::paginate(5);
    }

    public function getById(string $id): Album|Performer|null
    {
        return Performer::find($id);
    }

    public function getByPerformerName($name): Album|Performer|null
    {
        return Performer::where('name', $name)->first();
    }
}
