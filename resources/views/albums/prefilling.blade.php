@extends('layouts.app')
@section('content')
    <form method="POST" action="{{ empty($albumId) ? route('albums.prefillingStore') : route('albums.prefillingUpdate', $albumId) }}" style="width: 60%;" class="align-self-center">
        @csrf
        @if(!empty($albumId))
            @method('patch')
        @endif
        <p>Выберите исполнителя альбома {{ $data['album_name'] }}. И мы добавим описание и обложку альбома за вас.</p>
        <select class="form-select mt-5" data-live-search="true" name="album_data">
                @foreach($albums as $album)
                    <option value="{{ json_encode([$album['artist'], $album['name']]) }}">{{ $album['artist'] }}</option>
                @endforeach
        </select>
        <input type="text" name="album_name" value="{{ $data['album_name'] }}" style="display: none;">
        <button type="submit" class="btn btn-primary mt-5">Добавить информацию об альбоме</button>
    </form>
@endsection
