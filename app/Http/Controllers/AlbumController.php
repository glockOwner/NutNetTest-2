<?php

namespace App\Http\Controllers;

use App\Components\LastFM;
use App\Http\Filters\AlbumFilter;
use App\Http\Filters\PerformerFilter;
use App\Http\Requests\AlbumRequest;
use App\Http\Requests\FilterRequest;
use App\Models\Album;
use App\Repositories\AlbumRepository;
use App\Repositories\PerformerRepository;
use App\Services\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class AlbumController extends Controller
{
    private Service $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }


    public function index(AlbumRepository $albumRepository, PerformerRepository $performerRepository, FilterRequest $request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        $data = $request->validated();
        $filter = App::makeWith(AlbumFilter::class, ['queryParams' => $data]);
        $performers = $performerRepository->getAll();
        $albums = $albumRepository->getWithFilter($filter);
        return view('albums.index', compact('albums', 'performers'));
    }

    public function create(PerformerRepository $repository): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        $performers = $repository->getAll();
        return view('albums.create', compact('performers'));
    }

    public function prefilling(Request $request, AlbumRepository $repository, string $albumId = ''): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse
    {
        $data = $request->validate(
            [
                'album_name' => 'required|string|max:125'
            ]
        );
        $albums = $repository->getAlbumsFromApiByName($data['album_name']);
        return isset($albums[0]) ? view('albums.prefilling', compact('albums', 'data', 'albumId')) : redirect()->back()->with('error', 'Альбом не был найден');
    }

    public function store(AlbumRepository $repository, AlbumRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $fileKey = isset($data['cover']) ? 'cover' : '';
        $album = $repository->getAlbumOfPerformer($data['album_name'], $data['performer_id']);
        if (empty($album)) {
            $this->service->store($data, $request, $fileKey, Album::class);
            return redirect()->route('albums.index')->with('success', 'Альбом успешно добавлен');
        }
        return redirect()->route('albums.index')->with('error', 'Ошибка добавления альбома');
    }

    public function storeWithPrefilling(Request $request, AlbumRepository $albumRepository, PerformerRepository $performerRepository): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate(
            [
                'album_data' => 'json',
            ]
        );
        $data = $this->service->decodeAlbumJsonData($data);
        $performer = $performerRepository->getByName($data['name']);
        if (empty($performer)) {
            $performerData['name'] = $data['name'];
            $performer = $this->service->storeArtistWithPrefilling($performerData);
        } else {
            $performer = $performerRepository->getByName($data['name']);
        }
        $album = $albumRepository->getAlbumOfPerformer($data['album_name'], $performer->id);
        if (empty($album)) {
            $data['performer_id'] = $performer->id;
            if ($this->service->storeAlbumWithPrefilling($data)) {
                return redirect()->route('albums.index')->with('success', 'Альбом успешно добавлен');
            }
        }
        return redirect()->route('albums.index')->with('error', 'Ошибка добавления альбома');
    }

    public function edit(AlbumRepository $albumRepository,PerformerRepository $performerRepository, string $albumId): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        $performers = $performerRepository->getAll();
        $album = $albumRepository->getById($albumId);
        return view('albums.edit', compact('album', 'performers'));
    }

    public function update(AlbumRepository $repository, AlbumRequest $request, string $albumId): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $fileKey = isset($data['cover']) ? 'cover' : '';
        $album = $repository->getById($albumId);
        if (isset($album)) {
            $this->service->update($data, $request, $fileKey, $album);
            return redirect()->route('albums.index')->with('success', 'Информация об альбоме успешно обновлена');
        }
        return redirect()->route('albums.index')->with('error', 'Ошибка обновления информации об альбоме');
    }

    public function updateWithPrefilling(Request $request, AlbumRepository $albumRepository, PerformerRepository $performerRepository, string $albumId): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate(
            [
                'album_data' => 'json',
            ]
        );
        $data = $this->service->decodeAlbumJsonData($data);
        $performer = $performerRepository->getByName($data['name']);
        if (empty($performer)) {
            $performerData['name'] = $data['name'];
            $performer = $this->service->storeArtistWithPrefilling($performerData);
        } else {
            $performer = $performerRepository->getByName($data['name']);
        }
        $album = $albumRepository->getById($albumId);
        if (isset($album)) {
            $data['performer_id'] = $performer->id;
            if ($this->service->updateAlbumWithPrefilling($data, $album)) {
                return redirect()->route('albums.index')->with('success', 'Альбом успешно обновлён');
            }
        }
        return redirect()->route('albums.index')->with('error', 'Ошибка обновления информации об альбоме');
    }

    public function delete(AlbumRepository $repository, string $albumId): \Illuminate\Http\RedirectResponse
    {
        $album = $repository->getById($albumId);
        if (isset($album)) {
            $this->service->delete($album);
            return redirect()->route('albums.index')->with('success', 'Альбом успешно удалён');
        }
        return redirect()->route('albums.index')->with('error', 'Ошибка удаления информации об альбоме');
    }
}
