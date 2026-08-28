<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockInDetail;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    public function index(Request $request)
    {
        $stockIns = StockIn::with(['user', 'warehouse'])
            ->when($request->search, fn ($q, $s) => $q->where('reference_number', 'like', "%{$s}%")
                ->orWhere('supplier', 'like', "%{$s}%"))
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('stock-ins.index', compact('stockIns'));
    }

    public function create()
    {
        $products = Product::active()->orderBy('name')->get();
        $warehouses = Warehouse::orderBy('name')->get();

        return view('stock-ins.create', compact('products', 'warehouses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'supplier' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'qty' => ['required', 'array'],
            'qty.*' => ['nullable', 'integer', 'min:0'],
            'cost' => ['required', 'array'],
            'cost.*' => ['nullable', 'integer', 'min:0'],
        ]);

        $items = collect($request->qty)->filter(fn ($qty) => (int) $qty > 0);

        if ($items->isEmpty()) {
            return back()->withInput()->with('error', 'Pilih minimal satu barang untuk transaksi barang masuk.');
        }

        $products = Product::whereIn('id', $items->keys())->get()->keyBy('id');

        foreach ($items as $productId => $qty) {
            if (! $products->get($productId)) {
                return back()->withInput()->with('error', 'Ada barang yang tidak ditemukan.');
            }
        }

        $receipt = DB::transaction(function () use ($items, $products, $request) {
            $totalCost = 0;
            $lines = [];

            foreach ($items as $productId => $qty) {
                $cost = (int) ($request->cost[$productId] ?? $products->get($productId)->cost);
                $subtotal = $cost * (int) $qty;
                $totalCost += $subtotal;

                $lines[] = [
                    'product_id' => $productId,
                    'qty' => (int) $qty,
                    'cost' => $cost,
                    'subtotal' => $subtotal,
                ];
            }

            $stockIn = StockIn::create([
                'reference_number' => StockIn::generateReferenceNumber(),
                'warehouse_id' => $request->warehouse_id,
                'user_id' => Auth::id(),
                'supplier' => $request->supplier,
                'total_cost' => $totalCost,
                'notes' => $request->notes,
            ]);

            foreach ($lines as $line) {
                StockInDetail::create([
                    'stock_in_id' => $stockIn->id,
                    'product_id' => $line['product_id'],
                    'qty' => $line['qty'],
                    'cost' => $line['cost'],
                    'subtotal' => $line['subtotal'],
                ]);

                $product = $products->get($line['product_id']);
                $product->increment('stock', $line['qty']);
                $product->update(['cost' => $line['cost']]);
            }

            return $stockIn;
        });

        return redirect()->route('stock-ins.show', $receipt)
            ->with('success', 'Transaksi barang masuk berhasil disimpan.');
    }

    public function show(StockIn $stockIn)
    {
        $stockIn->load(['user', 'warehouse', 'details.product']);

        return view('stock-ins.show', compact('stockIn'));
    }
}
