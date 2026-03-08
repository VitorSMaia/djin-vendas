<?php

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\InventoryMovement;
use App\Models\SaleItem;
use App\Models\Sale;

echo "=== Iniciando Teste de Integridade de Estoque ===\n";

$user = User::first() ?? User::factory()->create();
$category = Category::first() ?? Category::query()->create(['name' => 'Teste Categoria']);

$product = Product::query()->create([
    'category_id' => $category->id,
    'name' => 'Teste Avaria ' . rand(100, 999),
    'price' => 5.00,
    'cost_price' => 2.00,
    'stock' => 10,
    'status' => 'active',
]);

echo "Produto criado. Estoque inicial: {$product->stock}\n";

// Test 1: Manual Inventory Movement (Avaria)
InventoryMovement::create([
    'product_id' => $product->id,
    'user_id' => $user->id,
    'type' => 'OUT_LOSS',
    'quantity' => -2,
    'reason' => 'Derreteu no sol'
]);

$product->refresh();
echo "Após avaria (-2). Estoque atual (esperado 8): {$product->stock}\n";
$movement = InventoryMovement::where('product_id', $product->id)->latest()->first();
echo "Log gerado: Type = {$movement->type}, Qty = {$movement->quantity}\n";

// Test 2: Sale Item Observer Test
// Simular venda
$sale = Sale::query()->create([
    'user_id' => $user->id,
    'sale_date' => now(),
    'total' => $product->price * 3,
]);

$saleItem = SaleItem::query()->create([
    'sale_id' => $sale->id,
    'product_id' => $product->id,
    'quantity' => 3,
    'unit_price' => $product->price,
    'total_price' => $product->price * 3,
]);

$product->refresh();
echo "Após venda (-3). Estoque atual (esperado 5): {$product->stock}\n";
echo "Custo salvo no item de venda (esperado 2.00): {$saleItem->unit_cost}\n";

$movement2 = InventoryMovement::where('product_id', $product->id)->latest('id')->first();
echo "Log gerado pela Venda: Type = {$movement2->type}, Qty = {$movement2->quantity}, Reason: {$movement2->reason}\n";

echo "=== Fim do Teste ===\n";
