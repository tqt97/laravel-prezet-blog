<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

// Route for creating a post
Route::get('posts', [PostController::class, 'create'])->name('posts.create');
Route::post('posts', [PostController::class, 'store'])->name('posts.store');

Route::get('/', function () {
    return view('welcome');
});

// Add the feed routes. this will add /feed.atom, /feed.rss, and /feed.json
Route::feeds();

require __DIR__.'/prezet.php';
