<?php

use Illuminate\Support\Facades\Route;




use App\Models\Post;
use App\Models\Agenda;
use App\Models\Announcement;
use App\Models\Page;
use Illuminate\Support\Str;

$serveSPA = function (\Illuminate\Http\Request $request) {
    $path = public_path('index.html');
    if (!file_exists($path)) {
        abort(404, "File index.html tidak ditemukan.");
    }
    
    $html = file_get_contents($path);
    $uri = $request->path();
    
    $title = null;
    $description = null;
    $image = null;
    $url = url($uri);
    
    $getImageUrl = function($imgPath) {
        if (!$imgPath) return null;
        if (Str::startsWith($imgPath, 'http')) return $imgPath;
        $imgPath = ltrim($imgPath, '/');
        return Str::startsWith($imgPath, 'storage/') ? url($imgPath) : url('storage/' . $imgPath);
    };
    
    try {
        if (Str::startsWith($uri, 'berita/') || Str::startsWith($uri, 'news-pesantren/') || Str::startsWith($uri, 'artikel/')) {
            $slug = collect(explode('/', $uri))->last();
            $post = Post::with('coverImage')->where('slug', $slug)->first();
            if ($post) {
                $title = $post->title;
                $description = Str::limit(strip_tags($post->content), 150);
                if ($post->coverImage) $image = $getImageUrl($post->coverImage->file_path);
            }
        } elseif (Str::startsWith($uri, 'agenda/')) {
            $slug = collect(explode('/', $uri))->last();
            $agenda = Agenda::where('slug', $slug)->first();
            if ($agenda) {
                $title = "Agenda: " . $agenda->title;
                $description = Str::limit(strip_tags($agenda->content), 150);
            }
        } elseif (Str::startsWith($uri, 'pengumuman/')) {
            $slug = collect(explode('/', $uri))->last();
            $announcement = Announcement::where('slug', $slug)->first();
            if ($announcement) {
                $title = "Pengumuman: " . $announcement->title;
                $description = Str::limit(strip_tags($announcement->content), 150);
            }
        } elseif (Str::startsWith($uri, 'profil/')) {
            $slug = collect(explode('/', $uri))->last();
            $page = Page::with('imageRelation')->where('slug', $slug)->first();
            if ($page) {
                $title = $page->title;
                $description = Str::limit(strip_tags($page->content), 150);
                if ($page->imageRelation) $image = $getImageUrl($page->imageRelation->file_path);
                elseif ($page->image) $image = $getImageUrl($page->image);
            }
        }

        if ($title) {
            $fullTitle = htmlspecialchars($title . " - PP. Miftahul Ulum Panyeppen", ENT_QUOTES);
            $html = preg_replace('/<title>.*?<\/title>/s', "<title>{$fullTitle}</title>", $html);
            $html = preg_replace('/<meta property="og:title" content=".*?" \/>/s', '<meta property="og:title" content="'.$fullTitle.'" />', $html);
            $html = preg_replace('/<meta name="twitter:title" content=".*?" \/>/s', '<meta name="twitter:title" content="'.$fullTitle.'" />', $html);
        }
        
        if ($description) {
            $descEscaped = htmlspecialchars($description, ENT_QUOTES);
            $html = preg_replace('/<meta name="description" content=".*?" \/>/s', '<meta name="description" content="'.$descEscaped.'" />', $html);
            $html = preg_replace('/<meta property="og:description" content=".*?" \/>/s', '<meta property="og:description" content="'.$descEscaped.'" />', $html);
        }
        
        if ($image) {
            $html = preg_replace('/<meta property="og:image" content=".*?" \/>/s', '<meta property="og:image" content="'.$image.'" />', $html);
            $html = preg_replace('/<meta name="twitter:image" content=".*?" \/>/s', '<meta name="twitter:image" content="'.$image.'" />', $html);
        }
        
        $html = preg_replace('/<meta property="og:url" content=".*?" \/>/s', '<meta property="og:url" content="'.htmlspecialchars($url).'" />', $html);

    } catch (\Exception $e) {
        // Abaikan error SEO agar aplikasi tetap jalan
    }
    
    return response($html);
};

Route::get('/', $serveSPA);
Route::fallback($serveSPA);
