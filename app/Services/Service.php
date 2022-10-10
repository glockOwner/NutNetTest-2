<?php

namespace App\Services;

use App\Models\Performer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Service
{
    public function store(array $data, Request $request, string $fileKey, string $model): void
    {
        $data = $this->uploadFile($request, $fileKey, $data);
        $model::create($data);
    }

    public function delete(Model $model): void
    {
        if (isset($model->img_path)) {
            $this->deleteFile($model);
        }
        $model->delete();
    }

    public function update(array $data, Request $request, string $fileKey, Model $model): void
    {
        $data = $this->uploadFile($request, $fileKey, $data);
        $model->update($data);
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
