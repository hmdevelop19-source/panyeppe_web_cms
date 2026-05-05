<?php

use Illuminate\Support\Facades\Route;

Route::get('/test-config', function() {
    return [
        'app_url_env' => env('APP_URL'),
        'public_path_env' => env('PUBLIC_PATH'),
        'public_path_resolved' => public_path(),
        'index_exists' => file_exists(public_path('index.html')),
    ];
});

Route::fallback(function () {
    // Jalankan React SPA jika file index.html ada di folder publik
    if (file_exists(public_path('index.html'))) {
        return file_get_contents(public_path('index.html'));
    }
    abort(404);
});
