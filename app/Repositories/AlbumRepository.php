<?php

namespace App\Repositories;

use App\Models\Album;
use App\Models\Performer;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class AlbumRepository implements RepositoryInterface
{

    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return Album::all();
    }

    public function getById(string $id): Album|Performer|null
    {
        return Album::find($id);
    }

    public function getByName(string $name): Album|Performer|null
    {
        return Album::where('album_name', $name)->first();
    }

    public function getAlbumOfPerformer(string $name, int $performer_id): ?Album
    {
        return Album::where('album_name', $name)->where('performer_id', $performer_id)->first();
    }

    public function getWithFilter($filter): ?LengthAwarePaginator
    {
        return Album::filter($filter)->paginate(5);
    }
}
