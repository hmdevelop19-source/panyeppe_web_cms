<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAgendaRequest;
use App\Http\Requests\UpdateAgendaRequest;
use App\Http\Resources\AgendaResource;
use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $agendas = Agenda::when($request->search, function ($query, $search) {
            $query->where('title', 'like', "%{$search}%");
        })
            ->when($request->trashed === 'true', function ($query) {
                $query->onlyTrashed();
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        return AgendaResource::collection($agendas);
    }

    public function store(StoreAgendaRequest $request)
    {
        $validated = $request->validated();
        $agenda = Agenda::create($validated);

        return (new AgendaResource($agenda))
            ->additional(['message' => 'Agenda berhasil ditambahkan.']);
    }

    public function show(Agenda $agenda)
    {
        return new AgendaResource($agenda);
    }

    public function update(UpdateAgendaRequest $request, Agenda $agenda)
    {
        $validated = $request->validated();

        $agenda->update($validated);

        return (new AgendaResource($agenda))
            ->additional(['message' => 'Agenda berhasil diperbarui.']);
    }

    public function destroy(Agenda $agenda)
    {
        $agenda->delete();

        return response()->json([
            'message' => 'Agenda berhasil dihapus.',
        ]);
    }

    public function restore($id)
    {
        $agenda = Agenda::onlyTrashed()->findOrFail($id);
        $agenda->restore();

        return response()->json([
            'message' => 'Agenda berhasil direstore.',
        ]);
    }

    public function forceDelete($id)
    {
        $agenda = Agenda::onlyTrashed()->findOrFail($id);
        $agenda->forceDelete();

        return response()->json([
            'message' => 'Agenda berhasil dihapus permanen.',
        ]);
    }
}
