<?php

use Illuminate\Support\Facades\Route;

Route::fallback(function () {
    // Jalankan React SPA jika file index.html ada di folder publik
    if (file_exists(public_path('index.html'))) {
        return file_get_contents(public_path('index.html'));
    }
    abort(404);
});
