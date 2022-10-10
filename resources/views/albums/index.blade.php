@extends('layouts.app')
@section('content')
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
                <td><img style="max-width: 200px; max-height: 100px;" class="card-img" src="{{isset($album->img_path) ? asset('storage/' . $album->img_path) : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT3ycU5bXgAg5HX2jZduC39vQ0p9sCH8VwWLg&usqp=CAU'}}"></td>
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
            {{ $albums->links() }}
        </div>
    </div>
@endsection