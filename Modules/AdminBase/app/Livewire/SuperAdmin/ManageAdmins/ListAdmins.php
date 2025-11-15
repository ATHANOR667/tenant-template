<?php

namespace Modules\AdminBase\Livewire\SuperAdmin\ManageAdmins;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\AdminBase\Models\Admin;

class ListAdmins extends Component
{
    use WithPagination;

    public string $search = '';

    protected array $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected  $listeners = [
        'dataUpdate' => 'refreshAdminsList',
        'adminCreated' => 'refreshAdminsList',
        'adminUpdated' => 'refreshAdminsList',
        'adminDeleted' => 'refreshAdminsList',
        'refreshAdminsList' => 'refreshAdminsList',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function refreshAdminsList(): void
    {
        $this->resetPage();
        $this->render();
    }

    public function deleteAdmin(string $adminId): void
    {
        try {
            $admin = Admin::find($adminId);

            if (!$admin) {
                $message = "Administrateur non trouvé.";
                $this->dispatch('flash-error', message: $message);
                return;
            }

//            if ($admin->hasRole('super-admin', 'admin)) {
//                session()->flash('error', "Impossible de supprimer un administrateur Super Admin.");
//                return;
//            }

            $adminName = $admin->nom . ' ' . $admin->prenom;
            $admin->delete();

            $message = "L'administrateur '{$adminName}' a été supprimé avec succès.";
            $this->dispatch('flash-success', message: $message);
            $this->refreshAdminsList();
            $this->dispatch('dataUpdate');

        } catch (\Exception $e) {
            $message = "Erreur lors de la suppression de l'administrateur: " . $e->getMessage();
            $this->dispatch('flash-error', message: $message);
            Log::error('ListAdmins - Erreur deleteAdmin: ' . $e->getMessage(), ['exception' => $e]);
        }
    }

    public function restoreAdmin(string $adminId): void
    {
        try {
            $admin = Admin::withTrashed()->find($adminId);

            if (!$admin) {
                $message = "Administrateur non trouvé.";
                $this->dispatch('flash-error', message: $message);
                return;
            }

            if (!$admin->trashed()) {
                $message = "L'administrateur n'est pas supprimé.";
                $this->dispatch('flash-error', message: $message);
                return;
            }

            $adminName = $admin->nom . ' ' . $admin->prenom;
            $admin->restore();

            $message = "L'administrateur '{$adminName}' a été restauré avec succès.";
            $this->dispatch('flash-success', message: $message);
            $this->refreshAdminsList();
            $this->dispatch('dataUpdate');
        } catch (\Exception $e) {
            $message = "Erreur lors de la restauration de l'administrateur: " . $e->getMessage();
            $this->dispatch('flash-error', message: $message);
            Log::error('ListAdmins - Erreur restoreAdmin: ' . $e->getMessage(), ['exception' => $e]);
        }
    }


    public function openAdminProfileCard(string $adminId): void
    {
      $this->dispatch('openAdminProfileCard', adminId: $adminId);
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory
    {
        $admins = Admin::query()
            ->with('roles')
            ->where(function ($query) {

            })
            ->when($this->search, function ($query) {
                $query->where('nom', 'like', '%' . $this->search . '%')
                    ->orWhere('prenom', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('matricule', 'like', '%' . $this->search . '%')
                    ->orWhereHas('roles', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->where('guard_name', 'admin');
                    });
            })
            ->orderBy('nom')
            ->withTrashed()
            ->paginate(10);

        return view('adminbase::livewire.super-admin.manage-admins.list-admins', [
            'admins' => $admins,
        ]);
    }
}
