<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use App\Models\StockTransfer;
use App\Models\StockTransferDetail;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockTransferController extends Controller
{
    public function index(Request $request)
    {
        $transfers = StockTransfer::with(['fromWarehouse', 'toWarehouse', 'user'])
            ->orderByDesc('id')
            ->paginate(10);

        return view('stock-transfers.index', compact('transfers'));
    }

    public function create()
    {
        $warehouses = Warehouse::orderBy('name')->get();
        $products = Product::active()->orderBy('name')->get();

        return view('stock-transfers.create', compact('warehouses', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'from_warehouse_id' => ['required', 'exists:warehouses,id', 'different:to_warehouse_id'],
            'to_warehouse_id' => ['required', 'exists:warehouses,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'qty' => ['required', 'array'],
            'qty.*' => ['nullable', 'integer', 'min:0'],
        ]);

        $items = collect($request->qty)->filter(fn ($qty) => (int) $qty > 0);

        if ($items->isEmpty()) {
            return back()->withInput()->with('error', 'Pilih minimal satu barang untuk transfer.');
        }

        foreach ($items as $productId => $qty) {
            $available = Stock::qtyOf($productId, $data['from_warehouse_id']);
            if ($qty > $available) {
                $product = Product::find($productId);
                return back()->withInput()->with('error', "Stok {$product->name} di gudang asal tidak mencukupi (sisa {$available}).");
            }
        }

        $transfer = DB::transaction(function () use ($items, $data) {
            $transfer = StockTransfer::create([
                'reference_number' => StockTransfer::generateReferenceNumber(),
                'from_warehouse_id' => $data['from_warehouse_id'],
                'to_warehouse_id' => $data['to_warehouse_id'],
                'user_id' => Auth::id(),
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($items as $productId => $qty) {
                StockTransferDetail::create([
                    'stock_transfer_id' => $transfer->id,
                    'product_id' => $productId,
                    'qty' => (int) $qty,
                ]);

                Stock::subtractQty($productId, $data['from_warehouse_id'], (int) $qty);
                Stock::addQty($productId, $data['to_warehouse_id'], (int) $qty);
            }

            return $transfer;
        });

        return redirect()->route('stock-transfers.show', $transfer)
            ->with('success', 'Transfer barang antar gudang berhasil disimpan.');
    }

    public function show(StockTransfer $stockTransfer)
    {
        $stockTransfer->load(['fromWarehouse', 'toWarehouse', 'user', 'details.product']);

        return view('stock-transfers.show', compact('stockTransfer'));
    }

    public function cancel(StockTransfer $stockTransfer)
    {
        if ($stockTransfer->status === 'dibatalkan') {
            return back()->with('error', 'Transfer ini sudah dibatalkan sebelumnya.');
        }

        try {
            DB::transaction(function () use ($stockTransfer) {
                foreach ($stockTransfer->details as $detail) {
                    $availableAtTarget = Stock::qtyOf($detail->product_id, $stockTransfer->to_warehouse_id);
                    if ($detail->qty > $availableAtTarget) {
                        throw new \RuntimeException("Stok {$detail->product->name} di gudang tujuan sudah berkurang, transfer tidak bisa dibatalkan.");
                    }
                }

                foreach ($stockTransfer->details as $detail) {
                    Stock::subtractQty($detail->product_id, $stockTransfer->to_warehouse_id, $detail->qty);
                    Stock::addQty($detail->product_id, $stockTransfer->from_warehouse_id, $detail->qty);
                }

                $stockTransfer->update([
                    'status' => 'dibatalkan',
                    'canceled_by' => Auth::id(),
                    'canceled_at' => now(),
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Transfer berhasil dibatalkan, stok dikembalikan ke gudang asal.');
    }
}