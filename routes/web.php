<?php

use App\Post;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

/*
 * Get a list of forums.
 */
Route::get('/', function () {
    $forums = \App\Forum::where('showprivate', 0)->where('threadcount', '>', 1)->orderby('replycount', 'desc')->get();

    return view('welcome', compact('forums'));
})->name('welcome');

/*
 * Get current forum and list of threads.
 */
Route::get('/forum/{id}', function ($id) {
    $forum = \App\Forum::whereNotIn('forumid', [1, 3, 4, 5, 6, 7, 8])->findOrFail($id);
    $threads = \App\Thread::where('forumid', $id)->where('open', 1)->where('visible', 1)->orderBy('dateline', 'desc')->paginate(10);

    return view('thread', compact('forum', 'threads'));
})->name('forum');

/*
 * Get current thread and list of posts.
 */
Route::get('/forum/posts/{thread_id}', function ($id) {
    $thread = \App\Thread::whereNotIn('forumid', [1, 3, 4, 5, 6, 7, 8])->where('visible', 1)->where('open', 1)->findOrFail($id);
    $posts = \App\Post::where('threadid', $id)->where('visible', 1)->where('userid', '>', 0)->orderBy('dateline')->paginate(10);

    return view('post', compact('thread', 'posts'));
})->name('thread');

/*
 * Get user
 */
Route::get('/forum/user/{id}', function ($id) {
    $user = \App\ForumUser::findOrFail($id);

    return view('user', compact('user'));
})->name('user');

Route::get('/search', function () {

    $validator = Validator::make(request()->all(), [
        'query' => 'required|min:1'
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $query = request()->input('query');
    $posts = Post::search($query)->with('thread.forum')->minScore(1.0)->paginate(10);

    return view('search', compact('posts'));

})->name('search');
