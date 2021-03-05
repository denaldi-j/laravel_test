@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 style="padding: 15px; background-color: #8fd19e; vertical-align: middle; margin-bottom: 10px">Category : {{ $data->name }}</h3>
        @foreach($data->book as $book)
            <div class="media mb-2">
                <img class="align-self-start mr-3" src="{{ url('cover_images/'.$book->cover) }}" style="height: 300px" alt="Generic placeholder image">
                <div class="media-body">
                    <h4 class="mt-0">{{ $book->title }}</h4>
                    <h5>by {{ $book->author }}</h5>
                    <p>{{ $book->description }}</p>
                </div>
            </div>
            <hr>
        @endforeach
    </div>

@endsection
