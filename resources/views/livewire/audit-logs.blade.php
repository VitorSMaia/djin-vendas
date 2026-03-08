<div class="p-6">
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Logs de Auditoria</h1>
            <p class="text-slate-500 dark:text-slate-400">Rastreabilidade completa de alterações no sistema.</p>
        </div>

        <div class="flex flex-col md:flex-row items-stretch md:items-center gap-2 md:gap-4">
            <select wire:model.live="entityType"
                class="rounded-xl border-slate-200 dark:bg-slate-800 dark:border-slate-700 dark:text-white text-sm">
                <option value="App\Models\Product">Produtos</option>
                <option value="App\Models\Sale">Vendas</option>
                <option value="System">Sistema</option>
            </select>

            <select wire:model.live="event"
                class="rounded-xl border-slate-200 dark:bg-slate-800 dark:border-slate-700 dark:text-white text-sm">
                <option value="">Todos os eventos</option>
                <option value="created">Criado</option>
                <option value="updated">Atualizado</option>
                <option value="deleted">Excluído</option>
                <option value="login">Login</option>
            </select>

            <input wire:model.live.debounce.500ms="userId" type="text" placeholder="Filtrar por usuário (ID)"
                class="rounded-xl border-slate-200 dark:bg-slate-800 dark:border-slate-700 dark:text-white text-sm px-3 py-2 w-full md:w-48" />
        </div>
    </div>

    <div
        class="bg-white dark:bg-[#1e293b] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        @livewire('dynamic-table', [
            'model' => \App\Models\AuditLog::class,
            'extraFilters' => $extraFilters,
            'sortField' => 'timestamp',
            'sortDirection' => 'desc',
            'columns' => [
                [
                    'label' => 'Evento',
                    'field' => 'event',
                    'sortable' => false,
                    'view' => 'components.table.status-badge', // Reutilizando badge
                ],
                [
                    'label' => 'Mensagem',
                    'field' => 'message',
                    'sortable' => false,
                ],
                [
                    'label' => 'Data/Hora',
                    'field' => 'timestamp',
                    'sortable' => true, // SK no DynamoDB
                    'type' => 'date',
                    'format' => 'd/m/Y H:i:s',
                ],
                [
                    'label' => 'Usuário',
                    'field' => 'user_id',
                    'sortable' => false,
                ],
                [
                    'label' => 'IP',
                    'field' => 'ip_address',
                    'sortable' => false,
                ],
            ],
            'perPage' => 15,
        ])
    </div>
</div>
