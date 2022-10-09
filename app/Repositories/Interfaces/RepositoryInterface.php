<?php

namespace App\Repositories\Interfaces;

use App\Models\Album;
use App\Models\Performer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    public function getAll(): ?LengthAwarePaginator;
    public function getById(string $id): Album|Performer|null;
    public function getByPerformerName($name): Album|Performer|null;
}
