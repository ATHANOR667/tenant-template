<?php

namespace Modules\AdminBase\Livewire\SuperAdmin\Auth;

use Livewire\Attributes\On;
use Livewire\Component;
use Modules\AdminBase\Models\SuperAdmin;

class ProfileInfoCard extends Component
{
    public SuperAdmin $user;

    #[on('dataUpdate')]
    public function actualize(): void
    {
        $this->mount($this->user);
    }



    public function mount(SuperAdmin $user): void
    {
        $this->user = $user;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory
    {
        return view('adminbase::livewire.super-admin.auth.profile-info-card');
    }
}
