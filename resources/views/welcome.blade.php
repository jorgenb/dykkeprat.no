@extends('layouts.app')

@section('content')
    <h1>Forum</h1>
    <hr>
    <div class="table-responsive">
        <table class="table table-striped table-hover " id="forum">
            <thead>
            <tr>
                <th>Navn</th>
                <th class="hidden-xs hidden-sm hidden-md">Tråder / Innlegg</th>
            </tr>
            </thead>
            <tbody>
            @foreach($forums as $forum)
                <tr>
                    <td><a href="{{ url('/forum', $forum->forumid) }}">{{ $forum->title }}</a><p class="small">{{ $forum->description }}</p></td>
                    <td class="hidden-xs hidden-sm hidden-md"><span class="badge">{{ $forum->threadcount }}</span> / <span class="badge">{{ $forum->replycount }}</span></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection