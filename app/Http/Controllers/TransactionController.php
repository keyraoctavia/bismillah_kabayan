<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::with('user')
            ->when($request->date, fn ($q, $date) => $q->whereDate('created_at', $date))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('details.product', 'user');

        return view('transactions.show', compact('transaction'));
    }

    public function cancel(Transaction $transaction)
    {
        if ($transaction->status === 'dibatalkan') {
            return back()->with('error', 'Transaksi ini sudah dibatalkan sebelumnya.');
        }

        DB::transaction(function () use ($transaction) {
            foreach ($transaction->details as $detail) {
                if ($transaction->warehouse_id) {
                    Stock::addQty($detail->product_id, $transaction->warehouse_id, $detail->qty);
                }
                $detail->product->increment('stock', $detail->qty);
            }

            $transaction->update([
                'status' => 'dibatalkan',
                'canceled_by' => Auth::id(),
                'canceled_at' => now(),
            ]);
        });

        return back()->with('success', 'Transaksi berhasil dibatalkan. Riwayat tetap tersimpan, stok dikembalikan.');
    }
}