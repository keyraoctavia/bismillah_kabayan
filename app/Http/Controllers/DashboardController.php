<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalBarang = Product::count();
        $totalGudang = Warehouse::count();
        $totalPelanggan = Customer::count();

        $totalPenjualanHariIni = Transaction::whereDate('created_at', today())
            ->where('status', '!=', 'dibatalkan')
            ->sum('total_price');

        $barangStokTerendah = Product::active()
            ->orderBy('stock')
            ->limit(5)
            ->get(['id', 'name', 'code', 'stock', 'unit', 'image']);

        return view('dashboard.index', compact(
            'totalBarang',
            'totalGudang',
            'totalPelanggan',
            'totalPenjualanHariIni',
            'barangStokTerendah'
        ));
    }
}