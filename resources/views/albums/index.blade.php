@extends('layouts.app')
@section('content')
    <div class="row-cols-12 align-self-center d-grid gap-1 mb-5">
        <form action="" method="GET" class="d-flex flex-column justify-content-center">
            <label for="performerFilter" class="form-label">Фильтр по исполнителю</label>
            <select class="form-select" aria-label="performerFilter" name="performer_id">
                @foreach($performers as $performer)
                    <option value="{{ $performer->id }}">{{ $performer->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary mt-2">Фильтр</button>
        </form>
    </div>
    @can('view', auth()->user())
        <div class="row-cols-12 align-self-center d-grid gap-1 mb-3">
            <a href="{{ route('albums.create') }}" class="btn btn-primary" type="button">Добавить альбом</a>
        </div>
    @endcan
    <table class="table table-borderless">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Название альбома</th>
            <th scope="col">Исполнитель</th>
            <th scope="col">Описание</th>
            <th scope="col">Обложка</th>
            @can('view', auth()->user())
                <th scope="col">Действия</th>
            @endcan
        </tr>
        </thead>
        <tbody>
        @foreach($albums as $album)
            <tr>
                <th scope="row">{{$album->id}}</th>
                <td>{{$album->album_name}}</td>
                <td>{{$album->performer->name}}</td>
                <td width="600">{{$album->description}}</td>
                <td><img style="max-width: 200px; max-height: 100px;" class="card-img" src="@if(isset($album->img_path) and $album->is_api){{ $album->img_path }}@elseif(isset($album->img_path) and !$album->is_api){{ asset('storage/' . $album->img_path) }} @elseif(empty($album->img_path)){{env('NO_IMAGE_URL')}}@endif"></td>
                @can('view', auth()->user())
                    <td class="d-flex flex-column">
                        <a href="{{ route('albums.edit', $album->id) }}" type="button" class="btn btn-secondary mt-2">Изменить</a>
                        <form action="{{ route('albums.delete', $album->id) }}" method="POST" style="display: inline-block; width: 100%;" class="mt-2">
                            @csrf
                            @method('delete')
                            <button style="width: 100%;" type="submit" class="btn btn-secondary" value="deleteAlbum">Удалить</button>
                        </form>
                    </td>
                @endcan
            </tr>
        @endforeach
        </tbody>
    </table>
    @if(session('success'))
        <div class="row-cols-9 align-self-center mt-5">
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="row-cols-9 align-self-center mt-5">
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        </div>
    @endif
    <div class="row d-flex justify-content-center align-self-center mt-5" style="align-self: end;">
        <div class="row">
            {{ $albums->withQueryString()->links() }}
        </div>
    </div>
@endsection
