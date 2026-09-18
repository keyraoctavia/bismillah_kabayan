<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    protected $fillable = ['product_id', 'warehouse_id', 'qty'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public static function qtyOf(int $productId, int $warehouseId): int
    {
        return (int) static::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->value('qty');
    }

    public static function addQty(int $productId, int $warehouseId, int $qty): self
    {
        $stock = static::firstOrCreate(
            ['product_id' => $productId, 'warehouse_id' => $warehouseId],
            ['qty' => 0]
        );
        $stock->increment('qty', $qty);

        return $stock->fresh();
    }

    public static function subtractQty(int $productId, int $warehouseId, int $qty): self
    {
        $stock = static::firstOrCreate(
            ['product_id' => $productId, 'warehouse_id' => $warehouseId],
            ['qty' => 0]
        );

        if ($stock->qty < $qty) {
            throw new \RuntimeException('Stok tidak mencukupi di gudang ini.');
        }

        $stock->decrement('qty', $qty);

        return $stock->fresh();
    }
}