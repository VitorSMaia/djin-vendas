<?php

namespace App\Livewire\Products;

use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class InventoryManager extends Component
{
    public $search = '';

    // Modal state
    public $showModal = false;
    public $selectedProduct = null;
    public $actionType = ''; // 'IN', 'OUT_SALE', 'OUT_LOSS'
    public $quantity = 1;
    public $reason = '';

    protected $listeners = [
        'quickAdjust' => 'openModal',
        'refresh-manager' => '$refresh',
        'deleteProduct' => 'deleteProduct',
        'editProduct' => 'editProduct'
    ];

    public function deleteProduct($id)
    {
        $productId = is_array($id) ? ($id['id'] ?? null) : $id;

        if (!$productId)
            return;

        $product = Product::find($productId);
        if ($product) {
            $product->delete();
            $this->dispatch('refresh-table');
            session()->flash('message', 'Produto excluído do estoque com sucesso!');
        }
    }

    // Edit Product State
    public $showEditModal = false;
    public $editProductId = null;
    public $editName = '';
    public $editPrice = 0;
    public $editCostPrice = 0;
    public $editSku = '';
    public $editStatus = 'active';

    public function editProduct($id)
    {
        $productId = is_array($id) ? ($id['id'] ?? $id['productId'] ?? null) : $id;
        $product = Product::find($productId);
        if ($product) {
            $this->editProductId = $product->id;
            $this->editName = $product->name;
            $this->editPrice = $product->price;
            $this->editCostPrice = $product->cost_price;
            $this->editSku = $product->sku;
            $this->editStatus = $product->status;
            $this->showEditModal = true;
        }
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editProductId = null;
    }

    public function updateProduct()
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editPrice' => 'required|numeric|min:0',
            'editCostPrice' => 'numeric|min:0',
            'editSku' => 'required|string|max:50',
            'editStatus' => 'required|in:active,inactive',
        ]);

        $product = Product::find($this->editProductId);
        if ($product) {
            $product->update([
                'name' => $this->editName,
                'price' => $this->editPrice,
                'cost_price' => $this->editCostPrice,
                'sku' => $this->editSku,
                'status' => $this->editStatus,
            ]);

            $this->dispatch('refresh-table');
            $this->closeEditModal();
            session()->flash('message', 'Produto atualizado com sucesso!');
        }
    }


    public function getStatsProperty()
    {
        return [
            'total' => Product::where('status', 'active')->count(),
            'in_stock' => Product::where('status', 'active')->where('stock', '>', 10)->count(),
            'low_stock' => Product::where('status', 'active')->where('stock', '>', 0)->where('stock', '<=', 10)->count(),
            'out_of_stock' => Product::where('status', 'active')->where('stock', '<=', 0)->count(),
        ];
    }

    public function openModal($id, $type = 'IN')
    {
        $this->selectedProduct = Product::find($id);
        $this->actionType = $type;
        $this->quantity = 1;
        $this->reason = '';
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedProduct = null;
    }

    public function submitMovement()
    {
        $this->validate([
            'quantity' => 'required|integer|min:1',
            'reason' => $this->actionType === 'OUT_LOSS' ? 'required|string|max:255' : 'nullable|string|max:255',
        ]);

        if (!$this->selectedProduct)
            return;

        $qty = $this->actionType === 'IN' ? $this->quantity : -$this->quantity;

        if ($this->actionType !== 'IN' && $this->selectedProduct->stock < $this->quantity) {
            session()->flash('error', 'Estoque indisponível para esta quantia.');
            return;
        }

        InventoryMovement::create([
            'product_id' => $this->selectedProduct->id,
            'user_id' => Auth::id(),
            'type' => $this->actionType,
            'quantity' => $qty,
            'reason' => $this->reason ?: ($this->actionType === 'IN' ? 'Reposição' : 'Ajuste Manual'),
        ]);

        $this->dispatch('refresh-table');
        $this->closeModal();
        session()->flash('message', 'Estoque atualizado com sucesso!');
    }

    public function render()
    {
        return view('livewire.products.inventory-manager', [
            'stats' => $this->stats
        ])->layout('layouts.app');
    }
}
