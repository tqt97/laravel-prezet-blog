<?php

use App\Http\Controllers\Prezet\ImageController;
use App\Http\Controllers\Prezet\IndexController;
use App\Http\Controllers\Prezet\OgimageController;
use App\Http\Controllers\Prezet\SearchController;
use App\Http\Controllers\Prezet\ShowController;
use App\Http\Controllers\Prezet\TranslationController;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;

Route::withoutMiddleware([
    ShareErrorsFromSession::class,
    StartSession::class,
    VerifyCsrfToken::class,
])
    ->middleware([SetLocale::class])
    ->group(function () {
        Route::get('prezet/search', SearchController::class)->name('prezet.search');

        Route::get('prezet/img/{path}', ImageController::class)
            ->name('prezet.image')
            ->where('path', '.*');

        Route::get('/prezet/ogimage/{slug}', OgimageController::class)
            ->name('prezet.ogimage')
            ->where('slug', '.*');

        Route::get('/', IndexController::class)
            ->name('prezet.index');

        // Translation routes - must come before the catch-all route
        Route::get('/{slug}/translate', [TranslationController::class, 'create'])
            ->name('prezet.translate.create')
            ->where('slug', '.*');

        Route::post('/{slug}/translate', [TranslationController::class, 'store'])
            ->name('prezet.translate.store')
            ->where('slug', '.*');

        Route::get('/{slug}/translate/{lang}', [TranslationController::class, 'translate'])
            ->name('prezet.translate.show')
            ->where('slug', '.*');

        // Catch-all route for articles - must come last
        Route::get('/{slug}', ShowController::class)
            ->name('prezet.show')
            ->where('slug', '.*'); // https://laravel.com/docs/11.x/routing#parameters-encoded-forward-slashes
    });
