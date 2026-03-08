<?php

namespace App\Livewire\Sales;

use App\Models\Category;
use App\Models\Product;
use App\Services\Sales\CreateSaleService;
use Livewire\Component;

class Register extends Component
{
    public $categories;
    public $selectedCategoryId;
    public $products = [];
    public $cart = []; // [product_id => quantity]
    public $total = 0;

    protected $rules = [
        'cart' => 'array',
    ];

    public function mount()
    {
        $this->categories = Category::query()
            ->orderBy('name')
            ->get();
        if ($this->categories->count() > 0) {
            $this->selectCategory($this->categories->first()->id);
        }
    }

    public function selectCategory($categoryId): void
    {
        $this->selectedCategoryId = $categoryId;
        $this->products = Product::query()
            ->where('category_id', $categoryId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
    }

    public function addToCart($productId): void
    {
        $product = Product::query()
            ->whereKey($productId)
            ->where('status', 'active')
            ->first();

        if ($product === null) {
            return;
        }

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]++;
        } else {
            $this->cart[$productId] = 1;
        }
        $this->calculateTotal();
    }

    public function removeFromCart($productId): void
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]--;
            if ($this->cart[$productId] <= 0) {
                unset($this->cart[$productId]);
            }
        }
        $this->calculateTotal();
    }

    public function calculateTotal(): void
    {
        $this->total = 0;
        foreach ($this->cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $this->total += $product->price * $quantity;
            }
        }
    }

    public function checkout(CreateSaleService $createSaleService): void
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Carrinho vazio!');
            return;
        }

        try {
            $createSaleService->handle($this->cart);

            $this->cart = [];
            $this->total = 0;

            session()->flash('message', 'Venda realizada com sucesso!');
        } catch (\Throwable $exception) {
            report($exception);

            session()->flash('error', $exception->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.sales.register')->layout('layouts.app');
    }
}
