<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use App\Http\Resources\AnnouncementResource;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $announcements = Announcement::when($request->search, function ($query, $search) {
            $query->where('title', 'like', "%{$search}%");
        })
            ->when($request->trashed === 'true', function ($query) {
                $query->onlyTrashed();
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        return AnnouncementResource::collection($announcements);
    }

    public function store(StoreAnnouncementRequest $request)
    {
        $validated = $request->validated();
        $announcement = Announcement::create($validated);

        Cache::increment('cache_v_announcements');
        Cache::forget('home_data');

        return (new AnnouncementResource($announcement))
            ->additional(['message' => 'Pengumuman berhasil diterbitkan.']);
    }

    public function show(Announcement $announcement)
    {
        return new AnnouncementResource($announcement);
    }

    public function update(UpdateAnnouncementRequest $request, Announcement $announcement)
    {
        $validated = $request->validated();

        $announcement->update($validated);

        Cache::increment('cache_v_announcements');
        Cache::forget('home_data');

        return (new AnnouncementResource($announcement))
            ->additional(['message' => 'Pengumuman berhasil diperbarui.']);
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        Cache::increment('cache_v_announcements');
        Cache::forget('home_data');

        return response()->json([
            'message' => 'Pengumuman berhasil dihapus.',
        ]);
    }

    public function restore($id)
    {
        $announcement = Announcement::onlyTrashed()->findOrFail($id);
        $announcement->restore();

        Cache::increment('cache_v_announcements');
        Cache::forget('home_data');

        return response()->json([
            'message' => 'Pengumuman berhasil direstore.',
        ]);
    }

    public function forceDelete($id)
    {
        $announcement = Announcement::onlyTrashed()->findOrFail($id);
        $announcement->forceDelete();

        Cache::increment('cache_v_announcements');
        Cache::forget('home_data');

        return response()->json([
            'message' => 'Pengumuman berhasil dihapus permanen.',
        ]);
    }
}
