<?php

namespace App\Livewire\Products;

use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class InventoryManager extends Component
{
    public $products;
    public $search = '';

    // Modal state
    public $showModal = false;
    public $selectedProduct = null;
    public $actionType = ''; // 'IN', 'OUT_SALE', 'OUT_LOSS'
    public $quantity = 1;
    public $reason = '';

    public function mount()
    {
        $this->loadProducts();
    }

    public function loadProducts()
    {
        $this->products = Product::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'ilike', '%' . $this->search . '%');
            })
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
    }

    public function updatedSearch()
    {
        $this->loadProducts();
    }

    public function openModal($productId, $type)
    {
        $this->selectedProduct = Product::find($productId);
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
            'reason' => $this->reason ?: null,
        ]);

        $this->loadProducts();
        $this->closeModal();
        session()->flash('message', 'Estoque atualizado com sucesso!');
    }

    public function render()
    {
        return view('livewire.products.inventory-manager')->layout('layouts.app');
    }
}
