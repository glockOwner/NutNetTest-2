@extends('layouts.app')
@section('content')
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
                    <td><img style="max-width: 200px; max-height: 100px;" class="card-img" src="{{isset($performer->img_path) ? asset('storage/' . $performer->img_path) : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT3ycU5bXgAg5HX2jZduC39vQ0p9sCH8VwWLg&usqp=CAU'}}"></td>
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
            {{$performers->links()}}
        </div>
    </div>
@endsection
