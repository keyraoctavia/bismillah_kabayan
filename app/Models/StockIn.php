<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number', 'warehouse_id', 'user_id', 'supplier', 'total_cost', 'notes',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(StockInDetail::class);
    }

    public static function generateReferenceNumber(): string
    {
        $prefix = 'BM-' . now()->format('Ymd') . '-';
        $last = self::where('reference_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->first();

        $number = $last ? ((int) substr($last->reference_number, -4)) + 1 : 1;

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
