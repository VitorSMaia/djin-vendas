<?php

namespace App\Services\Sales;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CreateSaleService
{
    /**
     * Cria uma venda completa com itens a partir de um carrinho.
     *
     * @param  array<int, int>  $cart  [product_id => quantity]
     */
    public function handle(array $cart): Sale
    {
        if (empty($cart)) {
            throw new InvalidArgumentException('Carrinho vazio.');
        }

        $cart = array_filter($cart, static fn ($qty): bool => (int) $qty > 0);

        if (empty($cart)) {
            throw new InvalidArgumentException('Carrinho vazio.');
        }

        $productIds = array_keys($cart);

        /** @var \Illuminate\Database\Eloquent\Collection<int, Product> $products */
        $products = Product::query()
            ->whereIn('id', $productIds)
            ->where('status', 'active')
            ->get()
            ->keyBy('id');

        if ($products->count() !== count($productIds)) {
            throw new InvalidArgumentException('Um ou mais produtos não foram encontrados ou estão inativos.');
        }

        return DB::transaction(function () use ($cart, $products): Sale {
            $total = 0;

            foreach ($cart as $productId => $quantity) {
                /** @var Product $product */
                $product = $products[$productId];

                if ($product->stock < $quantity) {
                    throw new InvalidArgumentException("Estoque insuficiente para {$product->name}.");
                }

                $total += $product->price * $quantity;
            }

            /** @var Sale $sale */
            $sale = Sale::query()->create([
                'user_id' => Auth::id(),
                'sale_date' => now(),
                'total' => $total,
            ]);

            foreach ($cart as $productId => $quantity) {
                /** @var Product $product */
                $product = $products[$productId];

                SaleItem::query()->create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'total_price' => $product->price * $quantity,
                ]);
            }

            return $sale->load(['items.product']);
        });
    }
}

