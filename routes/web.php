<?php

use Illuminate\Support\Facades\Route;

Route::get('/test-config', function() {
    return [
        'app_url_env' => env('APP_URL'),
        'app_url_config' => config('app.url'),
        'storage_url_test' => Storage::url('test.png'),
        'public_path_resolved' => public_path(),
    ];
});

Route::get('/', function() {
    $path = public_path('index.html');
    if (file_exists($path)) {
        return file_get_contents($path);
    }
    return "File index.html tidak ditemukan di: " . $path;
});

Route::fallback(function () {
    // Jalankan React SPA jika file index.html ada di folder publik
    if (file_exists(public_path('index.html'))) {
        return file_get_contents(public_path('index.html'));
    }
    abort(404);
});
