<?php

namespace App\Services;

use App\Components\LastFM;
use App\Models\Album;
use App\Models\Performer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use LastFmApi\Exception\ApiFailedException;
use Psr\Log\LoggerInterface;

class Service
{
    private LoggerInterface $logger;
    private LastFM $lastFmApi;

    public function __construct(LoggerInterface $logger, LastFM $lastFmApi)
    {
        $this->logger = $logger;
        $this->lastFmApi = $lastFmApi;
    }


    public function store(array $data, Request $request, string $fileKey, string $model): void
    {
        $data = $this->uploadFile($request, $fileKey, $data);
        $model::create($data);
        $logMessage = $model === Performer::class ? "Добавлен исполнитель $data[name]" : "Добавлен альбом $data[album_name]";
        $this->logger->channel(env("STORE_CHANNEL"))->log('info', $logMessage);
    }

    public function storeArtistWithPrefilling(array $data): bool|Performer
    {
        try {
            $artistInfo = $this->lastFmApi->getArtistInfo($data['name']);
            $data['img_path'] = !empty($artistInfo['image']['large']) ? $artistInfo['image']['large'] : null;
            $data['is_api'] = true;
            $performer = Performer::create($data);
            $logMessage = "Добавлен исполнитель $data[name]";
            $this->logger->channel(env("STORE_CHANNEL"))->log('info', $logMessage);
            return $performer;
        } catch (ApiFailedException $exception) {
            return false;
        }
    }

    public function storeAlbumWithPrefilling(array $data): bool
    {
        try {
            /*dd ($data);*/
            $albumInfo = $this->lastFmApi->getAlbumInfo($data['album_name'], $data['name']);
            unset($data['name']);
            $data['description'] = empty($albumInfo['wiki']['summary']) ? null : $albumInfo['wiki']['summary'];
            $data['img_path'] = empty($albumInfo['image']['medium']) ? null : $albumInfo['image']['medium'];
            $data['is_api'] = true;
            Album::create($data);
            $logMessage = "Добавлен альбом $data[album_name]";
            $this->logger->channel(env("STORE_CHANNEL"))->log('info', $logMessage);
            return true;
        } catch (ApiFailedException $exception) {
            return false;
        }
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

    public function updateArtistWithPrefilling(array $data, Performer $performer): bool
    {
        try {
            $artistInfo = $this->lastFmApi->getArtistInfo($data['name']);
            $data['img_path'] = !empty($artistInfo['image']['large']) ? $artistInfo['image']['large'] : null;
            $data['is_api'] = true;
            $oldName = $performer->name;
            $performer->update($data);
            $logMessage = "Обновлён исполнитель $oldName. Новое имя: $performer->name";
            $this->logger->channel(env("UPDATE_CHANNEL"))->log('info', $logMessage);
            return true;
        } catch (ApiFailedException $exception) {
            return false;
        }
    }

    public function updateAlbumWithPrefilling(array $data, Album $album): bool
    {
        try {
            $albumInfo = $this->lastFmApi->getAlbumInfo($data['album_name'], $data['name']);
            unset($data['name']);
            $data['description'] = empty($albumInfo['wiki']['summary']) ? null : $albumInfo['wiki']['summary'];
            $data['img_path'] = empty($albumInfo['image']['medium']) ? null : $albumInfo['image']['medium'];
            $data['is_api'] = true;
            $oldName = $album->album_name;
            $album->update($data);
            $logMessage = "Обновлён альбом $oldName. Новое название: $album->album_name";
            $this->logger->channel(env("UPDATE_CHANNEL"))->log('info', $logMessage);
            return true;
        } catch (ApiFailedException $exception) {
            return false;
        }
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

    public function decodeAlbumJsonData(array $data): array
    {
        $data['name'] = json_decode($data['album_data'])[0];
        $data['album_name'] = json_decode($data['album_data'])[1];
        unset($data['album_data']);
        return $data;
    }
}
