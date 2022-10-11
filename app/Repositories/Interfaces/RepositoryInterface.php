<?php

namespace App\Repositories\Interfaces;

use App\Models\Album;
use App\Models\Performer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    public function getAll(): \Illuminate\Database\Eloquent\Collection;
    public function getById(string $id): Album|Performer|null;
    public function getByName(string $name): Album|Performer|null;
    public function getWithFilter($filter): ?LengthAwarePaginator;
}
