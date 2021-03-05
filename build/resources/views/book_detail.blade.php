@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="media">
            <img class="align-self-start mr-3" src="{{ url('cover_images/'.$data->cover) }}" alt="Generic placeholder image">
            <div class="media-body">
                <h4 class="mt-0">{{ $data->title }}</h4>
                <h5>by {{ $data->author }}</h5>
                <hr>
                <p>{{ $data->description }}</p>
            </div>
        </div>
    </div>
@endsection
