@extends('layouts.app')

@section('content')
    <h1>Forum</h1>
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
                    <td>
                        <h5><a href="{{ url('/forum', $forum->forumid) }}">{{ $forum->title }}</a></h5>
                        <p>{{ $forum->description }}</p>
                    </td>
                    <td class="hidden-xs hidden-sm hidden-md">
                        <p class="ml-2">{{ $forum->threadcount }} / {{ $forum->replycount }}</p>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
