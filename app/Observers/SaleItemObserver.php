<?php

namespace App\Observers;

use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\SaleItem;
use Exception;
use Illuminate\Support\Facades\Auth;

class SaleItemObserver
{
    /**
     * Validate stock and set unit_cost before creating the sale item.
     */
    public function creating(SaleItem $saleItem): void
    {
        /** @var Product|null $product */
        $product = $saleItem->product ?? Product::find($saleItem->product_id);

        if ($product === null) {
            throw new Exception('Produto não encontrado para o item da venda.');
        }

        if ($product->stock < $saleItem->quantity) {
            throw new Exception("Estoque insuficiente para {$product->name}");
        }

        // Set unit cost at the time of sale
        $saleItem->unit_cost = $product->cost_price ?? 0;
    }

    /**
     * Generate an InventoryMovement after the sale item is created.
     */
    public function created(SaleItem $saleItem): void
    {
        InventoryMovement::create([
            'product_id' => $saleItem->product_id,
            'user_id' => Auth::id() ?? $saleItem->sale->user_id ?? 1, // Fallback safe
            'type' => 'OUT_SALE',
            'quantity' => -$saleItem->quantity, // Negative for stock reduction
            'reason' => 'Venda #' . $saleItem->sale_id,
        ]);
    }
}

