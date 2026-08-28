<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        $warehouses = Warehouse::query()
            ->when($request->search, fn ($query, $search) => $query->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('warehouses.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateWarehouse($request);

        Warehouse::create($data);

        return redirect()->route('warehouses.index')->with('success', 'Gudang berhasil ditambahkan!');
    }

    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $data = $this->validateWarehouse($request, $warehouse->id);

        $warehouse->update($data);

        return redirect()->route('warehouses.index')->with('success', 'Gudang berhasil diperbaharui!');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();

        return redirect()->route('warehouses.index')->with('success', 'Gudang berhasil dihapus');
    }

    private function validateWarehouse(Request $request, ?int $warehouseId = null): array
    {
        return $request->validate([
            'code' => 'required|string|max:255|unique:warehouses,code' . ($warehouseId ? ",{$warehouseId}" : ''),
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:1000',
        ]);
    }
}
