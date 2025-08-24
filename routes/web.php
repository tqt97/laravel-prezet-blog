<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Add the feed routes. this will add /feed.atom, /feed.rss, and /feed.json
Route::feeds();

require __DIR__.'/prezet.php';
