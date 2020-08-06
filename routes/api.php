<?php

use App\Thread;
use Illuminate\Support\Facades\Route;

Route::post('search_as_you_type', function () {
    $results = Thread::search(request()->input('query'))->with('forum')->minScore(1.0)->get();

    return $results;
})->name('search_as_you_type');
