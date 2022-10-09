@extends('layouts.app')
@section('content')
    <form method="POST" action="{{ route('performers.store') }}" enctype="multipart/form-data" style="width: 60%;" class="align-self-center">
        @csrf
        <div class="row mb-3">
            <label for="inputEmail3" class="col-sm-2 col-form-label">Имя исполнителя</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="inputEmail3" name="name" value="{{old('name')}}">
            </div>
            @error('name')
            <p class="text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="row mb-3">
            <label for="inputPassword3" class="col-sm-2 col-form-label">Фотография исполнителя</label>
            <div class="col-sm-10">
                <input type="file" class="form-control" id="inputPassword3" name="photo">
            </div>
            @error('photo')
            <p class="text-danger">{{$message}}</p>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Добавить исполнителя</button>
        <div class="col-sm-10 mt-5">
            <input class="btn btn-primary" type="submit" formaction="" value="Предзаполнение полей по полю Имя исполнителя">
        </div>
    </form>
@endsection
