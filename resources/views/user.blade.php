@extends('layouts.app')

@section('content')
    <div class="well">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <h4>{{ $user->username }}, {{ $user->usertitle }}</h4>
                <p><span class="glyphicon glyphicon-pencil"></span> {{ $user->posts }}</p>
                <p>Medlem siden {{ $user->joindate }}</p>
                @if ($user->homepage)
                    <p class="small lead"><a href="{{ url($user->homepage) }}">{{ $user->homepage }}</a></p>
                @endif
            </div>
        </div>
    </div>
@endsection
