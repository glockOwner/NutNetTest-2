<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlbumRequest;
use App\Models\Album;
use App\Repositories\AlbumRepository;
use App\Repositories\PerformerRepository;
use App\Services\Service;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    private Service $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }


    public function index(AlbumRepository $repository): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        $albums = $repository->getAll();
        return view('albums.index', compact('albums'));
    }

    public function create(PerformerRepository $repository): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        $performers = $repository->getAll();
        return view('albums.create', compact('performers'));
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

    public function edit(AlbumRepository $albumRepository,PerformerRepository $performerRepository, string $albumId): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        $performers = $performerRepository->getAll();
        $album = $albumRepository->getById($albumId);
        return view('albums.edit', compact('album', 'performers'));
    }

    public function update(AlbumRepository $repository, AlbumRequest $request, string $albumId)
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

    public function delete(AlbumRepository $repository, string $albumId)
    {
        $album = $repository->getById($albumId);
        if (isset($album)) {
            $this->service->delete($album);
            return redirect()->route('albums.index')->with('success', 'Альбом успешно удалён');
        }
        return redirect()->route('albums.index')->with('error', 'Ошибка удаления информации об альбоме');
    }
}
