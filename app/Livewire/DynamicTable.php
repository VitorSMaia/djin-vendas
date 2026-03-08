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
        $isDynamoDb = is_subclass_of($this->model, \Kitar\Dynamodb\Model\Model::class);
        $query = $this->model::query();

        // Aplicar filtros extras passados pelo componente pai
        foreach ($this->extraFilters as $field => $value) {
            if ($isDynamoDb) {
                $query->filter($field, '=', $value);
            } else {
                $query->where($field, '=', $value);
            }
        }

        if ($this->search && !empty($this->searchableColumns)) {
            if ($isDynamoDb) {
                // DynamoDB não suporta closures no where() da mesma forma que Eloquent
                // Para simplificar, vamos filtrar o primeiro campo e dar OR nos outros
                $first = true;
                foreach ($this->searchableColumns as $column) {
                    if ($first) {
                        $query->filter($column, 'contains', $this->search);
                        $first = false;
                    } else {
                        $query->orFilter($column, 'contains', $this->search);
                    }
                }
            } else {
                $query->where(function ($q) {
                    foreach ($this->searchableColumns as $column) {
                        $q->orWhere($column, 'like', '%' . $this->search . '%');
                    }
                });
            }
        }

        if ($this->statusFilterColumn && $this->statusFilter) {
            if ($isDynamoDb) {
                $query->filter($this->statusFilterColumn, '=', $this->statusFilter);
            } else {
                $query->where($this->statusFilterColumn, '=', $this->statusFilter);
            }
        }

        if ($isDynamoDb) {
            // DynamoDB sorting via ScanIndexForward (apenas para queries com Sort Key)
            if ($this->sortField === 'timestamp') {
                $query->scanIndexForward($this->sortDirection === 'asc');
            }

            // DynamoDB execute
            try {
                // Tenta query() se houver KeyCondition, senão scan()
                // O pacote Kitar decide based on wheres internos, mas podemos forçar
                $results = $query->query();
            } catch (\Exception $e) {
                $results = $query->scan();
            }

            // Paginação manual para DynamoDB results (coleção)
            $items = new \Illuminate\Pagination\LengthAwarePaginator(
                $results->forPage($this->getPage(), $this->perPage),
                $results->count(),
                $this->perPage,
                $this->getPage(),
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
            );
        } else {
            $items = $query->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage);
        }

        return view('livewire.dynamic-table', [
            'items' => $items,
        ]);
    }
}
