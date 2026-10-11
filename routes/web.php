<?php

use Illuminate\Support\Facades\Route;

Route::get('/berita/{slug}', App\Http\Controllers\NewsPageController::class)
    ->where('slug', '[A-Za-z0-9\-]+');
Route::get('/berita-sitemap.xml', App\Http\Controllers\SitemapController::class);

Route::view('/{path?}', 'app')->where('path', '.*');
