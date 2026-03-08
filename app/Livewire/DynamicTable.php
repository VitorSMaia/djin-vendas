<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class DynamicTable extends Component
{
    use WithPagination;

    protected $listeners = ['refresh-table' => '$refresh'];

    public $model;
    public $extraFilters = []; // Permite passar filtros adicionais (ex: ['auditable_type' => 'App\Models\Product'])
    public $columns = [];
    public $searchableColumns = [];
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $perPage = 10;

    public $statusFilterColumn = '';
    public $statusFilterOptions = [];
    public $statusFilter = '';

    public $editEvent;
    public $deleteEvent;

    protected $queryString = ['search', 'statusFilter', 'sortField', 'sortDirection'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = $this->model::query();

        // Aplicar filtros extras passados pelo componente pai
        foreach ($this->extraFilters as $field => $value) {
            $query->where($field, '=', $value);
        }

        if ($this->search && !empty($this->searchableColumns)) {
            $query->where(function ($q) {
                foreach ($this->searchableColumns as $column) {
                    $q->orWhere($column, 'like', '%' . $this->search . '%');
                }
            });
        }

        if ($this->statusFilterColumn && $this->statusFilter) {
            $query->where($this->statusFilterColumn, '=', $this->statusFilter);
        }

        $items = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.dynamic-table', [
            'items' => $items,
        ]);
    }
}
