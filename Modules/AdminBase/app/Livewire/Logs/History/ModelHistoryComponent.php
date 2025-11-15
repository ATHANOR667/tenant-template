<?php

namespace Modules\AdminBase\Livewire\Logs\History;

use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Attributes\On;
use Livewire\Component;

class ModelHistoryComponent extends Component
{
    public bool $showModal = false;
    public string|int $logableId;
    public string $logableType;
    public array $versions = [];
    public int $currentIndex = 0;

    #[On('showHistory')]
    public function showHistory($logableId, $logableType): void
    {
        $this->logableId = $logableId;
        $this->logableType = $logableType;
        $this->loadVersions();
        $this->showModal = true;
    }

    public function loadVersions(): void
    {
        $model = Relation::getMorphedModel($this->logableType)::withTrashed()->find($this->logableId);
        if ($model) {
            $this->versions = $model->getActivityVersions();
        }
    }

    /**
     * Ajoute une liste des champs modifiés pour chaque snapshot en comparant avec l'état précédent.
     */

    public function next(): void
    {
        if ($this->currentIndex < count($this->versions) - 1) {
            $this->currentIndex++;
        }
    }

    public function prev(): void
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
        }
    }

    public function close(): void
    {
        $this->showModal = false;
        $this->currentIndex = 0;
        $this->versions = [];
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('adminbase::livewire.logs.history.model-history-component');
    }
}
