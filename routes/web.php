<?php

use Illuminate\Support\Facades\Route;

Route::get('/test-config', function() {
    return [
        'app_url_config' => config('app.url'),
        'app_url_env' => env('APP_URL'),
        'public_path_env' => env('PUBLIC_PATH'),
        'public_path_resolved' => public_path(),
    ];
});

Route::fallback(function () {
    // Hanya dijalankan jika file index.html benar-benar ada (untuk mode single-domain)
    if (file_exists(public_path('index.html'))) {
        return file_get_contents(public_path('index.html'));
    }
    abort(404);
});
