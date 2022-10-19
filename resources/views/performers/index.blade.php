@extends('layouts.app')
@section('content')
    <div class="row-cols-12 align-self-center d-grid gap-1 mb-5">
        <form action="" method="GET" class="d-flex flex-column justify-content-center">
            <input class="form-control mr-2" type="search" placeholder="Поиск исполнителя" aria-label="Поиск исполнителя" name="name">
            <button class="btn btn-outline-success mt-2" type="submit">Поиск</button>
        </form>
    </div>
    @can('view', auth()->user())
        <div class="row-cols-12 align-self-center d-grid gap-1 mb-3">
            <a href="{{route('performers.create')}}" class="btn btn-primary" type="button">Добавить исполнителя</a>
        </div>
    @endcan
    <table class="table table-borderless">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Имя исполнителя</th>
            <th scope="col">Фотография</th>
            @can('view', auth()->user())
                <th scope="col">Действия</th>
            @endcan
        </tr>
        </thead>
        <tbody>
            @foreach($performers as $performer)
                <tr>
                    <th scope="row">{{$performer->id}}</th>
                    <td>{{$performer->name}}</td>
                    <td><img style="max-width: 200px; max-height: 100px;" class="card-img" src="@if(isset($performer->img_path) and $performer->is_api){{ $performer->img_path }}@elseif(isset($performer->img_path) and !$performer->is_api){{ asset('storage/' . $performer->img_path) }} @elseif(empty($performer->img_path)){{env('NO_IMAGE_URL')}}@endif"></td>
                    @can('view', auth()->user())
                        <td>
                            <a href="{{ route('performers.edit', $performer->id) }}" type="button" class="btn btn-secondary mr-4">Изменить</a>
                            <form action="{{ route('performers.delete', $performer->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-secondary" value="deleteAlbum">Удалить</button>
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
            {{ $performers->withQueryString()->links() }}
        </div>
    </div>
@endsection
