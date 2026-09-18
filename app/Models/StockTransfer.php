<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockTransfer extends Model
{
    protected $fillable = [
        'reference_number', 'from_warehouse_id', 'to_warehouse_id',
        'user_id', 'status', 'canceled_by', 'canceled_at', 'notes',
    ];

    public function fromWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(StockTransferDetail::class);
    }

    public static function generateReferenceNumber(): string
    {
        $prefix = 'TR-' . now()->format('Ymd') . '-';
        $last = self::where('reference_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->first();

        $number = $last ? ((int) substr($last->reference_number, -4)) + 1 : 1;

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}