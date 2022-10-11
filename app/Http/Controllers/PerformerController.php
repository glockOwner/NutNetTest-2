<?php

namespace App\Http\Controllers;

use App\Http\Filters\PerformerFilter;
use App\Http\Requests\FilterRequest;
use App\Http\Requests\PerformerRequest;
use App\Models\Performer;
use App\Repositories\PerformerRepository;
use App\Services\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class PerformerController extends Controller
{
    private Service $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index(PerformerRepository $repository, FilterRequest $request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        $data = $request->validated();
        $filter = App::makeWith(PerformerFilter::class, ['queryParams' => $data]);
        $performers = $repository->getWithFilter($filter);
        return view('performers.index', compact('performers'));
    }

    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        return view('performers.create');
    }

    public function store(PerformerRepository $repository, PerformerRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $fileKey = isset($data['photo']) ? 'photo' : '';
        $performer = $repository->getByName($data['name']);
        if (empty($performer)) {
            $this->service->store($data, $request, $fileKey, Performer::class);
            return redirect()->route('performers.index')->with('success', 'Исполнитель успешно добавлен');
        }
        return redirect()->route('performers.index')->with('error', 'Ошибка добавления исполнителя');
    }

    public function edit(PerformerRepository $repository, string $performerId): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        $performer = $repository->getById($performerId);
        return view('performers.edit', compact('performer'));
    }

    public function update(PerformerRepository $repository, PerformerRequest $request, string $performerId): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $fileKey = isset($data['photo']) ? 'photo' : '';
        $performer = $repository->getById($performerId);
        if (isset($performer)) {
            $this->service->update($data, $request, $fileKey, $performer);
            return redirect()->route('performers.index')->with('success', 'Информация об исполнтеле успешно обновлена');
        }
        return redirect()->route('performers.index')->with('error', 'Ошибка обновления информации об исполнителе');
    }

    public function delete(PerformerRepository $repository, string $performerId): \Illuminate\Http\RedirectResponse
    {
        $performer = $repository->getById($performerId);
        if (isset($performer)) {
            $this->service->delete($performer);
            return redirect()->route('performers.index')->with('success', 'Исполнитель успешно удалён');
        }
        return redirect()->route('performers.index')->with('error', 'Ошибка удаления исполнителя');
    }
}
