<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gourmet = Category::firstOrCreate(['name' => 'Gourmet'], ['description' => 'Sabores especiais e premium.']);
        $tradicional = Category::firstOrCreate(['name' => 'Tradicional'], ['description' => 'Sabores clássicos e favoritos.']);

        $nutella = Product::updateOrCreate(
            ['name' => 'Nutella'],
            [
                'category_id' => $gourmet->id,
                'price' => 5.00,
                'stock' => 50,
                'status' => 'active',
            ]
        );

        $maracuja = Product::updateOrCreate(
            ['name' => 'Maracujá'],
            [
                'category_id' => $tradicional->id,
                'price' => 3.50,
                'stock' => 100,
                'status' => 'active',
            ]
        );

        $pacoca = Product::updateOrCreate(
            ['name' => 'Paçoca'],
            [
                'category_id' => $tradicional->id,
                'price' => 4.00,
                'stock' => 80,
                'status' => 'active',
            ]
        );

        // Generate 30 days of sales with items
        $products = [$nutella, $maracuja, $pacoca];
        $startDate = now()->subDays(30);

        for ($i = 0; $i <= 30; $i++) {
            $date = (clone $startDate)->addDays($i);

            // Random number of sales per day (5 to 20)
            $salesCount = random_int(5, 20);

            for ($j = 0; $j < $salesCount; $j++) {
                $itemsCount = random_int(1, 3);
                $cart = [];

                for ($k = 0; $k < $itemsCount; $k++) {
                    $product = $products[array_rand($products)];
                    $quantity = random_int(1, 5);

                    $cart[$product->id] = ($cart[$product->id] ?? 0) + $quantity;
                }

                DB::transaction(static function () use ($cart, $products, $date): void {
                    $total = 0;

                    foreach ($cart as $productId => $quantity) {
                        /** @var \App\Models\Product $product */
                        $product = collect($products)->firstWhere('id', $productId);
                        $total += $product->price * $quantity;
                    }

                    $sale = Sale::query()->create([
                        'user_id' => null,
                        'sale_date' => $date->copy()->addMinutes(random_int(0, 1440)),
                        'total' => $total,
                    ]);

                    foreach ($cart as $productId => $quantity) {
                        /** @var \App\Models\Product $product */
                        $product = collect($products)->firstWhere('id', $productId);

                        SaleItem::query()->create([
                            'sale_id' => $sale->id,
                            'product_id' => $product->id,
                            'quantity' => $quantity,
                            'unit_price' => $product->price,
                            'total_price' => $product->price * $quantity,
                        ]);
                    }
                });
            }
        }
    }
}
