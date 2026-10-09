<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SalesDetail;
use App\Models\User;
use App\Services\Inventory\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SaleManagementController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Sale::class);

        $from = $request->date('from') ?? today()->subDays(6);
        $to = $request->date('to') ?? today();

        $sales = Sale::with('user')
            ->whereBetween('occurred_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->when($request->payment_method, fn ($q, $m) => $q->where('payment_method', $m))
            ->when($request->filled('offline'), fn ($q) => $q->where('is_offline', $request->boolean('offline')))
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest('occurred_at')
            ->paginate(25)
            ->withQueryString();

        $totals = Sale::whereBetween('occurred_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->where('status', 'confirmed')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total) as revenue')
            ->groupBy('payment_method')
            ->get();

        return view('admin.sales.index', compact('sales', 'totals', 'from', 'to'));
    }

    public function show(Sale $sale)
    {
        Gate::authorize('view', $sale);

        $lines = SalesDetail::with('product')->where('sales_id', $sale->id)->get();   // until Sale.php is fixed
        $voidedBy = User::find($sale->voided_by);

        return view('admin.sales.show', compact('sale', 'lines', 'voidedBy'));

    }

    public function void(Request $request, Sale $sale, StockService $stock)
    {
        Gate::authorize('update', $sale);                // SalePolicy: admin only

        $data = $request->validate(['reason' => ['required', 'string', 'min:5', 'max:255']]);

        if ($sale->status === 'cancelled') {
            return back()->with('error', 'This sale is already voided.');
        }

        DB::transaction(function () use ($sale, $data, $stock, $request) {
            $sale = Sale::lockForUpdate()->findOrFail($sale->id);

            foreach (SalesDetail::with('product')->where('sales_id', $sale->id)->get() as $line) {
                $stock->restore($line->product, $line->quantity, 'sale_voided', $sale);
            }

            $sale->update([
                'status' => 'cancelled',
                'voided_by' => $request->user()->id,
                'voided_at' => now(),
                'void_reason' => $data['reason'],
            ]);
        });

        return back()->with('success', "Sale #{$sale->id} voided and stock returned.");
    }
}
