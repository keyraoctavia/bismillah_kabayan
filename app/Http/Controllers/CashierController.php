<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashierController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->when($request->search, fn ($query, $search) => $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
            }))
            ->orderBy('name')
            ->get();

        $warehouses = Warehouse::orderBy('name')->get();

        return view('cashier.index', compact('products', 'warehouses'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'qty' => ['required', 'array'],
            'qty.*' => ['nullable', 'integer', 'min:0'],
            'cash' => ['required', 'integer', 'min:0'],
        ]);

        $items = collect($request->qty)->filter(fn ($qty) => (int) $qty > 0);

        if ($items->isEmpty()) {
            return back()->withInput()->with('error', 'Pilih minimal satu produk untuk transaksi');
        }

        $products = Product::whereIn('id', $items->keys())->get()->keyBy('id');

        $totalPrice = 0;
        foreach ($items as $productId => $qty) {
            $product = $products->get($productId);

            if (! $product) {
                return back()->withInput()->with('error', 'Ada produk yang tidak ditemukan.');
            }

            $stokGudang = Stock::qtyOf($productId, $request->warehouse_id);
            if ($qty > $stokGudang) {
                return back()->withInput()->with('error', "Stok {$product->name} di gudang ini tidak mencukupi (sisa {$stokGudang}).");
            }

            $totalPrice += $product->price * $qty;
        }

        if ($request->cash < $totalPrice) {
            return back()->withInput()->with('error', 'Uang yang dibayarkan kurang dari total belanja.');
        }

        $receipt = DB::transaction(function () use ($items, $products, $totalPrice, $request) {
            $trx = Transaction::create([
                'invoice_number' => Transaction::generateInvoiceNumber(),
                'user_id' => Auth::id(),
                'warehouse_id' => $request->warehouse_id,
                'total_price' => $totalPrice,
                'cash' => $request->cash,
                'change' => $request->cash - $totalPrice,
            ]);

            $lines = [];

            foreach ($items as $productId => $qty) {
                $product = $products->get($productId);
                TransactionDetail::create([
                    'transaction_id' => $trx->id,
                    'product_id' => $product->id,
                    'price' => $product->price,
                    'qty' => $qty,
                    'subtotal' => $product->price * $qty,
                ]);

                Stock::subtractQty($product->id, $request->warehouse_id, (int) $qty);
                $product->decrement('stock', $qty);

                $lines[] = [
                    'name' => $product->name,
                    'qty' => $qty,
                    'price' => $product->price,
                    'subtotal' => $product->price * $qty,
                ];
            }

            return [
                'invoice_number' => $trx->invoice_number,
                'items' => $lines,
                'total_price' => $trx->total_price,
                'cash' => $trx->cash,
                'change' => $trx->change,
            ];
        });

        return redirect()->route('cashier.index')
            ->with('success', 'Transaksi berhasil disimpan.')
            ->with('receipt', $receipt);
    }
}