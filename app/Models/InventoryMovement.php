<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    protected $table = 'inventory_movements';

    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'reason',
        'date',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'date' => 'date',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected static function booted(): void
    {
        static::created(function (InventoryMovement $movement) {
            $product = $movement->product;
            if ($movement->type === 'entrada') {
                $product->increment('stock', $movement->quantity);
            } else {
                $product->decrement('stock', $movement->quantity);
            }
        });
    }
}
