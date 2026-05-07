<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->handle(Request::capture());

echo "CATEGORIES:\n";
print_r(DB::table('categories')->get(['id', 'name'])->toArray());
echo "\nPOSTS:\n";
print_r(DB::table('posts')
    ->join('categories', 'posts.category_id', '=', 'categories.id')
    ->select('posts.id', 'posts.title', 'categories.name as category_name')
    ->latest('posts.created_at')
    ->take(10)
    ->get()
    ->toArray());
