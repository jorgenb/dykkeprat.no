@extends('layouts.app')

@section('content')
    <h4>{{ $user->username }}, {{ $user->usertitle }}</h4>
    <p>Antall innlegg: {{ $user->posts }}</p>
    <p>Medlem siden {{ $user->joindate }}</p>
    @if ($user->homepage)
        <p class="small lead"><a href="{{ url($user->homepage) }}">{{ $user->homepage }}</a></p>
    @endif
@endsection
