<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AuditLog;
use Livewire\WithPagination;

class AuditLogs extends Component
{
    use WithPagination;

    public $entityType = 'App\Models\Product';
    public $event = '';
    public $userId = '';

    protected $queryString = [
        'entityType',
        'event',
        'userId',
    ];

    public function render()
    {
        $extraFilters = [
            'auditable_type' => $this->entityType,
        ];

        if ($this->event !== '') {
            $extraFilters['event'] = $this->event;
        }

        if ($this->userId !== '') {
            $extraFilters['user_id'] = $this->userId;
        }

        return view('livewire.audit-logs', [
            'extraFilters' => $extraFilters,
        ]);
    }

    public function updatedEntityType()
    {
        $this->resetPage();
    }

    public function updatedEvent(): void
    {
        $this->resetPage();
    }

    public function updatedUserId(): void
    {
        $this->resetPage();
    }
}
