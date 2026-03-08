<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Category;
use App\Models\Product;
use App\Services\Sales\CreateSaleService;

class SalesInterface extends Component
{
    private \App\Services\Logging\LoggerInterface $logger;

    public function boot(\App\Services\Logging\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public $cart = []; // [product_id => quantity]
    public $total = 0;

    // Modal & CRUD state
    public $showModal = false;
    public $isEditing = false;
    public $editingProductId = null;
    public $categories = [];

    // Form fields
    public $name = '';
    public $sku = '';
    public $price = '';
    public $stock = '';
    public $category_id = '';
    public $image_url = '';

    protected $listeners = [
        'quickAdjust' => 'quickAdjust',
        'editProduct' => 'openModal',
        'deleteProduct' => 'deleteProduct'
    ];

    public function mount()
    {
        $this->categories = Category::all();
    }

    public function openModal($productId = null)
    {
        $this->resetValidation();
        $this->reset(['name', 'sku', 'price', 'stock', 'category_id', 'image_url']);

        if ($productId) {
            $this->isEditing = true;
            $this->editingProductId = $productId;
            $product = Product::find($productId);
            $this->name = $product->name;
            $this->sku = $product->sku;
            $this->price = $product->price;
            $this->stock = $product->stock;
            $this->category_id = $product->category_id;
            $this->image_url = $product->image_url;
        } else {
            $this->isEditing = false;
            $this->editingProductId = null;
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function saveProduct()
    {
        $this->validate([
            'name' => 'required|min:3',
            'sku' => 'required|unique:products,sku,' . ($this->editingProductId ?? 'NULL'),
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image_url' => 'nullable|url',
        ]);

        $data = [
            'name' => $this->name,
            'sku' => $this->sku,
            'price' => $this->price,
            'stock' => $this->stock,
            'category_id' => $this->category_id,
            'image_url' => $this->image_url,
        ];

        if ($this->isEditing) {
            $product = Product::find($this->editingProductId);
            $product->update($data);

            $this->logger->info(
                "Produto '{$product->name}' atualizado",
                "ProductService",
                auth()->id(),
                Product::class,
                ['product_id' => $product->id, 'data' => $data]
            );

            session()->flash('message', 'Produto atualizado com sucesso!');
        } else {
            $product = Product::create($data);

            $this->logger->info(
                "Novo produto '{$product->name}' criado",
                "ProductService",
                auth()->id(),
                Product::class,
                ['product_id' => $product->id, 'data' => $data]
            );

            session()->flash('message', 'Produto criado com sucesso!');
        }

        $this->closeModal();
        $this->dispatch('refresh-table');
        $this->dispatch('message', session('message'));
    }

    public function deleteProduct($id)
    {
        $product = Product::find($id);
        if ($product) {
            $productName = $product->name;
            $productId = $product->id;
            $product->delete();

            $this->logger->warn(
                "Produto '{$productName}' excluído",
                "ProductService",
                auth()->id(),
                Product::class,
                ['product_id' => $productId]
            );

            $this->dispatch('refresh-table');
            $this->dispatch('message', 'Produto removido com sucesso!');
        }
    }

    public function quickAdjust($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->increment('stock');
            $this->dispatch('refresh-table');
            session()->flash('message', "Estoque de {$product->name} atualizado!");
        }
    }

    public function addToCart($productId)
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]++;
        } else {
            $this->cart[$productId] = 1;
        }
        $this->calculateTotal();
    }

    public function removeFromCart($productId)
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]--;
            if ($this->cart[$productId] <= 0) {
                unset($this->cart[$productId]);
            }
        }
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = 0;
        foreach ($this->cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $this->total += $product->price * $quantity;
            }
        }
    }

    public function checkout(CreateSaleService $createSaleService)
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
            $this->dispatch('refresh-table');
        } catch (\Throwable $exception) {
            report($exception);
            session()->flash('error', $exception->getMessage());
        }
    }

    public function getSummaryProperty()
    {
        return [
            'total_items' => Product::sum('stock'),
            'low_stock' => Product::where('stock', '<=', 5)->count(),
            'total_value' => Product::selectRaw('SUM(stock * price) as total')->value('total') ?? 0,
            'rotation' => '4.2x', // Mocked as requested in layout
        ];
    }

    public function render()
    {
        return view('livewire.sales-interface', [
            'summary' => $this->summary
        ])->layout('layouts.app');
    }
}
