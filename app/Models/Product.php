<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'sku',
        'stock',
        'cost',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'cost' => 'decimal:2',
            'price' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function getValueAttribute(): float
    {
        return (float) $this->stock * (float) $this->cost;
    }

    public function getMarginAttribute(): float
    {
        return (float) $this->price > 0
            ? (((float) $this->price - (float) $this->cost) / (float) $this->price) * 100
            : 0;
    }

    public function scopeLowStock(Builder $query, int $threshold = 5): Builder
    {
        return $query->where('stock', '<=', $threshold);
    }
}
