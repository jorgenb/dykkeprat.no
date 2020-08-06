@extends('layouts.app')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item" aria-current="page">
            <a href="{{ url('/') }}">Forum</a>
        </li>

        <li class="breadcrumb-item active" aria-current="page"><a
                href="{{ route('forum', $thread->forum->forumid) }}">{{ $thread->forum->title }}</a> </li>
    </ol>
</nav>
<hr>

<h1 class="mb-2">{{ $thread->title }}</h1>
<div class="col">
    @foreach($posts as $key => $post)
    <div class="row mb-4">
        @if ($key % 2 == 0)
        <div class="d-none d-lg-block border rounded-lg align-self-start mr-3 p-4 text-center">
            <img class="img-responsive" src="{{ $post->user->gravatar() }}" />
            <p class="p-4 text-center"><a href="{{ url('forum/user', $post->userid) }}">{{ $post->username }}</a></p>
        </div>
        <div class="media-body border rounded-lg p-4">
            <h5><a href="{{ url('forum/user', $post->userid) }}">{{ $post->username }}</a></h5>
            <small class="text-muted">{{ $post->dateline }}</small>
            <hr>
            <p class="overflow-auto">{!! $post->pagetext !!}</p>
        </div>
        @else
        <div class="media-body border rounded-lg p-4">
            <h5><a href="{{ url('forum/user', $post->userid) }}">{{ $post->username }}</a></h5>
            <small class="text-muted">{{ $post->dateline }}</small>
            <hr>
            <p class="overflow-auto">{!! $post->pagetext !!}</p>
        </div>
        <div class="d-none d-lg-block border rounded-lg align-self-start ml-3 p-4 text-center">
            <img class="img-responsive" src="{{ $post->user->gravatar() }}" />
            <p class="text-center"><a href="{{ url('forum/user', $post->userid) }}">{{ $post->username }}</a></p>
        </div>
        @endif
    </div>
    @endforeach
</div>
{!! $posts->links() !!}

@endsection
