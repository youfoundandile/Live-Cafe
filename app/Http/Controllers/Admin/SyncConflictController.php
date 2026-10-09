<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SyncConflict;
use App\Services\Admin\ReconciliationService;
use Illuminate\Http\Request;

class SyncConflictController extends Controller
{
    public function index()
    {
        $open = SyncConflict::open()->with(['sale.user', 'stockable'])->latest()->get();
        $recent = SyncConflict::where('status', 'resolved')->with(['sale', 'stockable', 'resolver'])
            ->latest('resolved_at')->take(20)->get();

        return view('admin.sync.index', compact('open', 'recent'));
    }

    public function resolve(Request $request, SyncConflict $conflict, ReconciliationService $service)
    {
        $data = $request->validate([
            'resolution' => ['required', 'in:recount,voided,accepted_loss'],
            'note' => ['required', 'string', 'max:255'],
            'actual_count' => ['required_if:resolution,recount', 'nullable', 'numeric', 'min:0'],
        ]);

        $service->resolve($conflict, $data['resolution'], $data['note'], $data['actual_count'] ?? null);

        return back()->with('success', 'Conflict resolved.');
    }
}
