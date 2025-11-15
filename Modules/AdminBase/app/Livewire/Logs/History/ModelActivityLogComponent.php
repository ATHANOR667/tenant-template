<?php

namespace Modules\AdminBase\Livewire\Logs\History;


use Livewire\Component;
use Livewire\WithPagination;
use Modules\AdminBase\Models\ModelActivityLog;

class ModelActivityLogComponent extends Component
{
    use WithPagination;

    public string $search = '';
    public string $operationFilter = '';
    public string $logableTypeFilter = '';
    public string $changedByTypeFilter = '';

    protected array $queryString = [
        'search' => ['except' => ''],
        'operationFilter' => ['except' => ''],
        'logableTypeFilter' => ['except' => ''],
        'changedByTypeFilter' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingOperationFilter(): void
    {
        $this->resetPage();
    }

    public function updatingLogableTypeFilter(): void
    {
        $this->resetPage();
    }

    public function updatingChangedByTypeFilter(): void
    {
        $this->resetPage();
    }

    public function showHistory($logableId, $logableType): void
    {
        $this->dispatch('showHistory', logableId: $logableId, logableType: $logableType);
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        $logableModels = ModelActivityLog::distinct()->pluck('logable_type')->sort()->toArray();
        $changedByModels = ModelActivityLog::distinct()->pluck('changed_by_type')->sort()->toArray();

        $logs = ModelActivityLog::query()
            ->with(['changed_by', 'logable'])
            ->when($this->search, function ($query) {
                $query->where('field_name', 'like', '%' . $this->search . '%')
                    ->orWhere('old_value', 'like', '%' . $this->search . '%')
                    ->orWhere('new_value', 'like', '%' . $this->search . '%');
            })
            ->when($this->operationFilter, function ($query) {
                $query->where('operation', $this->operationFilter);
            })
            ->when($this->logableTypeFilter, function ($query) {
                $query->where('logable_type',$this->logableTypeFilter );
            })
            ->when($this->changedByTypeFilter, function ($query) {
                $query->where('changed_by_type', $this->changedByTypeFilter );
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('adminbase::livewire.logs.history.model-activity-log-component', [
            'logs' => $logs,
            'logableModels' => $logableModels,
            'changedByModels' => $changedByModels,
        ]);
    }
}
