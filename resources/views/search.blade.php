@extends('layouts.app')

@section('content')

@if (count($posts) === 0)
<div class="text-center">
    <div class="alert alert-danger" role="alert">
        <h4 class="alert-heading">Oh no!!! Klarte ikke å finne akkurat det!</h4>
        <hr>
        <p>Prøv å søke etter noe annet</p>
    </div>
</div>
@endif

<div class="list-group list-group-flush">
    @foreach ($posts as $post)
    <a class="list-group-item list-group-item-action" href="{{ route('thread', $post->threadid) }}">
        @if (!$post->highlight->titleAsString)
        <h3>{!! $post->thread->title !!}</h3>
        @else
        <h3>{!! $post->highlight->titleAsString !!}</h3>
        @endif

        @if (!$post->highlight->parsedpagetextAsString)
        <p>
            <span class="text-muted">
                {{ $post->dateline }} ( {{ $post->username }} ) -
            </span>

            {!! $post->parsedpagetext !!}
        </p>
        @else
        <p>
            <span class="text-muted">
                {{ $post->dateline }} ( {{ $post->username }} ) -
            </span>
            {!! $post->highlight->parsedpagetextAsString !!}
        </p>
        @endif
    </a>
    @endforeach
</div>

{{ $posts->links() }}

@endsection
