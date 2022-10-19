<?php

namespace App\Repositories;

use App\Components\LastFM;
use App\Models\Album;
use App\Models\Performer;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use LastFmApi\Exception\NoResultsException;

class AlbumRepository implements RepositoryInterface
{
    private LastFM $lastFmApi;

    public function __construct(LastFM $lastFmApi)
    {
        $this->lastFmApi = $lastFmApi;
    }


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

    public function getAlbumsFromApiByName(string $album): array|bool
    {
        try {
            return $this->lastFmApi->searchAlbum($album)['results'];
        } catch (NoResultsException $exception) {
            return [];
        }
    }
}
