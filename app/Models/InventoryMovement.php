<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'reason',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::created(function (InventoryMovement $movement) {
            $product = $movement->product;
            if ($product) {
                $product->stock += $movement->quantity; // Se OUT_SALE for -1, ele vai subtrair porque é negativo
                $product->save();
            }
        });
    }
}
