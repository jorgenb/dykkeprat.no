@extends('layouts.app')

@section('content')

    <h1>{{ $forum->title }}</h1>

    <div class="row">
        <div class="col-md-12">
            <p class="lead">{{ $forum->description }}</p>
            {{ $threads->links() }}
        </div>
    </div>


    <div class="panel panel-default">
        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover ">
                <thead>
                <tr>
                    <th>Navn</th>
                    <th>Innlegg / Visninger</th>
                </tr>
                </thead>
                <tbody>
                @foreach($threads as $thread)
                    <tr>
                        <td>
                            <h5><a href="{{ url('forum/posts', $thread->threadid) }}">{{ $thread->title }}</a></h5>
                            <p>{{ $thread->postusername }}, {{ $thread->dateline }}</p>
                        </td>
                        <td>
                            <p>{{ $thread->replycount }} / {{ $thread->views }}</p>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="panel-footer">
            <div class="row">
                {{ $threads->links() }}
            </div>
        </div>
    </div>
@endsection
