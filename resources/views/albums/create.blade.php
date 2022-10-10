@extends('layouts.app')
@section('content')
    <form method="POST" action="{{ route('albums.store') }}" enctype="multipart/form-data" style="width: 60%;" class="align-self-center">
        @csrf
        <div class="row mb-3">
            <label for="inputEmail3" class="col-sm-2 col-form-label">Название альбома</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="inputEmail3" name="album_name" value="{{old('album_name')}}">
            </div>
            @error('album_name')
            <p class="text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="row mb-3">
            <label for="performerSelect" class="form-label">Выбор исполнителя</label>
            <select class="form-select" id="performerSelect" aria-label="Default select example" name="performer_id">
                @foreach($performers as $performer)
                    <option {{ old('performer_id') == $performer->id ? 'selected' : '' }} value="{{ $performer->id }}">{{ $performer->name }}</option>
                @endforeach
            </select>
            @error('performer_id')
            <p class="text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="row mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Описание</label>
            <textarea class="form-control" style="resize: none;" id="exampleFormControlTextarea1" rows="3" name="description">{{ old('description') }}</textarea>
            @error('description')
            <p class="text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="row mb-3">
            <label for="inputPassword3" class="col-sm-2 col-form-label">Обложка</label>
            <div class="col-sm-10">
                <input type="file" class="form-control" id="inputPassword3" name="cover">
            </div>
            @error('cover')
            <p class="text-danger">{{$message}}</p>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Добавить альбом</button>
        <div class="col-sm-10 mt-5">
            <input class="btn btn-primary" type="submit" formaction="" value="Предзаполнение полей по полю Название альбома">
        </div>
    </form>
@endsection
