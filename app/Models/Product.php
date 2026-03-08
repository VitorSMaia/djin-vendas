<?php

namespace App\Models;

use App\Traits\HasAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;
    use HasAudit;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'image_url',
        'price',
        'cost_price',
        'stock',
        'status',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function ($product) {
            $product->auditModelChange($product, 'created', [], $product->toArray());
        });

        static::updated(function ($product) {
            // Log audit if stock changed
            if ($product->wasChanged('stock')) {
                $product->auditModelChange(
                    $product,
                    'updated',
                    ['stock' => $product->getOriginal('stock')],
                    ['stock' => $product->stock]
                );
            }
        });

        static::deleted(function ($product) {
            $product->auditModelChange($product, 'deleted', $product->toArray(), []);
        });
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 5) {
            return 'Crítico';
        }

        if ($this->stock <= 10) {
            return 'Alerta';
        }

        if ($this->stock <= 15) {
            return 'Médio';
        }

        return 'Estável';
    }

    public function getStockPercentageAttribute(): int
    {
        $maxStock = 20; // Based on layout (X de 20 unidades)
        return min(100, round(($this->stock / $maxStock) * 100));
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }
}
