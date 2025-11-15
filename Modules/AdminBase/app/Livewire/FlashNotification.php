<?php

namespace Modules\AdminBase\Livewire;

use Livewire\Component;

class FlashNotification extends Component
{
    public $message = '';
    public $type = 'success';
    public $show = false;

    // Utilisation d'écouteurs spécifiques pour chaque type de notification
    protected $listeners = [
        'flash-success' => 'handleSuccess',
        'flash-error' => 'handleError',
        'flash-warning' => 'handleWarning',
        'flash-info' => 'handleInfo',
    ];

    public function handleSuccess($message): void
    {
        $this->showNotification($message, 'success');
    }

    public function handleError($message): void
    {
        $this->showNotification($message, 'error');
    }

    public function handleWarning($message): void
    {
        $this->showNotification($message, 'warning');
    }

    public function handleInfo($message): void
    {
        $this->showNotification($message, 'info');
    }

    private function showNotification($message, $type): void
    {
        $this->message = $message;
        $this->type = $type;
        $this->show = true;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('adminbase::livewire.flash-notification');
    }
}
