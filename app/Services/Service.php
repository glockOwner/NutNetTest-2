<?php

namespace App\Services;

use App\Models\Performer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Psr\Log\LoggerInterface;

class Service
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    public function store(array $data, Request $request, string $fileKey, string $model): void
    {
        $data = $this->uploadFile($request, $fileKey, $data);
        $model::create($data);
        $logMessage = $model === Performer::class ? "Добавлен исполнитель $data[name]" : "Добавлен альбом $data[album_name]";
        $this->logger->channel(env("STORE_CHANNEL"))->log('info', $logMessage);
    }

    public function delete(Model $model): void
    {
        if (isset($model->img_path)) {
            $this->deleteFile($model);
        }
        $model->delete();
        $logMessage = $model instanceof Performer ? "Удалён исполнитель $model->name" : "Удалён альбом $model->album_name";
        $this->logger->channel(env("DELETE_CHANNEL"))->log('info', $logMessage);
    }

    public function update(array $data, Request $request, string $fileKey, Model $model): void
    {
        $data = $this->uploadFile($request, $fileKey, $data);
        $oldName = $model instanceof Performer ? $model->name : $model->album_name;
        $model->update($data);
        $logMessage = $model instanceof Performer ? "Обновлён исполнитель $oldName. Новое имя: $model->name" : "Обновлён альбом $oldName. Новое название: $model->album_name";
        $this->logger->channel(env("UPDATE_CHANNEL"))->log('info', $logMessage);
    }

    private function uploadFile(Request $request, string $fileKey, array $data): array
    {
        if ($request->hasFile($fileKey) && !(Storage::exists("upload/{$request->file($fileKey)->getClientOriginalName()}"))) {
            $data['img_path'] = $request->$fileKey->storeAs('upload/', $request->file($fileKey)->getClientOriginalName());
        }
        elseif ($request->hasFile($fileKey)) {
            $data['img_path'] = "upload/{$request->file($fileKey)->getClientOriginalName()}";
        }
        unset($data[$fileKey]);
        return $data;
    }

    private function deleteFile(Model $model): void
    {
        Storage::delete($model->img_path);
    }
}
