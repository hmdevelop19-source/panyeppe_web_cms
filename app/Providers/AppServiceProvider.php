<?php

namespace App\Providers;

use App\Models\Agenda;
use App\Models\Announcement;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Leader;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\Video;
use App\Observers\CacheObserver;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($customPath = env('PUBLIC_PATH')) {
            $this->app->instance('path.public', base_path($customPath));
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        JsonResource::withoutWrapping();

        // Register Cache Observers
        Page::observe(CacheObserver::class);
        Post::observe(CacheObserver::class);
        Agenda::observe(CacheObserver::class);
        Announcement::observe(CacheObserver::class);
        Video::observe(CacheObserver::class);
        Banner::observe(CacheObserver::class);
        Setting::observe(CacheObserver::class);
        Category::observe(CacheObserver::class);
        Leader::observe(CacheObserver::class);
        Testimonial::observe(CacheObserver::class);
    }
}
