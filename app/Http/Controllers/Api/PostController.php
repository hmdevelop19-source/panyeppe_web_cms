<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::with(['category', 'user', 'coverImage'])
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->trashed === 'true', function ($query) {
                $query->onlyTrashed();
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        return PostResource::collection($posts);
    }

    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;

        // Penulis cannot publish directly
        if ($request->user()->cannot('publish', Post::class) && $validated['status'] === 'published') {
            $validated['status'] = 'pending';
        }

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        $post = Post::create($validated);

        return (new PostResource($post->load(['category', 'user', 'coverImage'])))
            ->additional(['message' => $post->status === 'pending' ? 'Berita berhasil diajukan untuk review.' : 'Berita berhasil diterbitkan.']);
    }

    public function show(Post $post)
    {
        return new PostResource($post->load(['category', 'user', 'coverImage']));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $validated = $request->validated();

        $this->authorize('update', $post);

        // Penulis cannot publish directly
        if ($request->user()->cannot('publish', Post::class) && $validated['status'] === 'published') {
            $validated['status'] = 'pending';
        }

        if ($validated['status'] === 'published' && ! $post->published_at) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        return (new PostResource($post->load(['category', 'user', 'coverImage'])))
            ->additional(['message' => $post->status === 'pending' ? 'Berita berhasil diajukan untuk review.' : 'Berita berhasil diperbarui.']);
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json([
            'message' => 'Berita berhasil dihapus.',
        ]);
    }

    public function restore($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->restore();

        return response()->json([
            'message' => 'Berita berhasil direstore.',
        ]);
    }

    public function forceDelete($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->forceDelete();

        return response()->json([
            'message' => 'Berita berhasil dihapus permanen.',
        ]);
    }
}
