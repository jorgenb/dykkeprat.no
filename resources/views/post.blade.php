@extends('layouts.app')

@section('content')

    <ol class="breadcrumb">
        <li><a href="{{ url('/') }}">Forum</a></li>
        <li class="active">{{ $thread->forum->title }}</li>
    </ol>

<!-- http://bootsnipp.com/snippets/featured/comment-posts-layout -->
<div class="row">
    <div class="col-md-12">
        <h2 class="page-header">{{ $thread->title }}</h2>
        <section class="comment-list">
            <!-- First Comment -->
            @foreach($posts as $key => $post)
                <article class="row">
                    @if ($key % 2 == 0)
                        <div class="col-md-2 col-sm-2 hidden-xs">
                            <figure class="thumbnail">
                                <img class="img-responsive" src="http://www.keita-gaming.com/assets/profile/default-avatar-c5d8ec086224cb6fc4e395f4ba3018c2.jpg" />
                                <figcaption class="text-center"><a href="{{ url('forum/user', $post->userid) }}">{{ $post->username }}</a></figcaption>
                            </figure>
                        </div>
                        <div class="col-md-10 col-sm-10">
                            <div class="panel panel-default arrow left">
                                <div class="panel-body">
                                    <header class="text-left">
                                        <div class="comment-user"><i class="fa fa-user"></i> <a href="{{ url('forum/user', $post->userid) }}">{{ $post->username }}</a></div>
                                        <time class="comment-date" datetime="{{ $post->dateline }}"><i class="fa fa-clock-o"></i> {{ $post->dateline }}</time>
                                        <hr>
                                    </header>
                                    <div class="comment-post">
                                        <p>
                                            {!! $post->pagetext !!}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-md-10 col-sm-10">
                            <div class="panel panel-default arrow right">
                                <div class="panel-body">
                                    <header class="text-right">
                                        <div class="comment-user"><i class="fa fa-user"></i> <a href="{{ url('forum/user', $post->userid) }}">{{ $post->username }}</a></div>
                                        <time class="comment-date" datetime="{{ $post->dateline }}"><i class="fa fa-clock-o"></i> {{ $post->dateline }}</time>
                                        <hr>
                                    </header>
                                    <div class="comment-post">
                                        <p>
                                            {!! $post->pagetext !!}.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 hidden-xs">
                            <figure class="thumbnail">
                                <img class="img-responsive" src="http://www.keita-gaming.com/assets/profile/default-avatar-c5d8ec086224cb6fc4e395f4ba3018c2.jpg" />
                                <figcaption class="text-center"><a href="{{ url('forum/user', $post->userid) }}">{{ $post->username }}</a></figcaption>
                            </figure>
                        </div>
                    @endif
                </article>
            @endforeach
        </section>
        <div class="pull-right">
            {!! $posts->links() !!}
        </div>

    </div>
</div>

@endsection
