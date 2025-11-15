<?php

namespace Modules\AdminBase\Livewire\SuperAdmin\ManageAdmins;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Modules\AdminBase\Models\Admin;
use Illuminate\Support\Facades\Session;

class AdminRoleSelector extends Component
{
    public array $selectedRoles = [];

    public array $currentRoles = [];

    public $availableRoles;

    public ?Admin $admin = null;


    public function mount(): void
    {
          $this->availableRoles = Role::where('guard_name', 'admin')->orderBy('name')->get();

        if ($this->admin) {

            $currentRoleIds = $this->admin->roles->pluck('id')->map('strval')->toArray();

            $this->currentRoles = $currentRoleIds;
            $this->selectedRoles = $currentRoleIds;
        } else {
            $this->currentRoles = [];
            $this->selectedRoles = [];
        }
    }


    #[On('adminCreated')]
    public function handleAdminCreated(string $adminCreated): void
    {
        // 1. Charger l'administrateur nouvellement créé
        $newAdmin = Admin::find($adminCreated);

        if (!$newAdmin) {
            $this->dispatch('flash-error', "Erreur : L'administrateur ID {$adminCreated} n'a pas été trouvé pour l'attribution des rôles.");
            return;
        }

        $this->performRoleSync($newAdmin);

        $this->admin = $newAdmin;
        $currentRoleIds = $this->admin->roles->pluck('id')->map('strval')->toArray();
        $this->currentRoles = $currentRoleIds;
        $this->selectedRoles = $currentRoleIds;

        $this->dispatch('refreshAdminsList');
        $this->dispatch('flash-success', "L'administrateur '{$newAdmin->name}' a été créé et les rôles ont été attribués.");

    }


    public function toggleRole(string $roleId): void
    {
        $roleId = (string) $roleId;

        if (in_array($roleId, $this->selectedRoles)) {
            $this->selectedRoles = array_filter($this->selectedRoles, fn($id) => $id !== $roleId);
        } else {
            $this->selectedRoles[] = $roleId;
        }

        // Ré-indexer le tableau après la suppression
        $this->selectedRoles = array_values($this->selectedRoles);
    }

    /**
     * Méthode interne pour centraliser la logique de synchronisation des rôles Spatie.
     * @param Admin $targetAdmin L'administrateur à qui appliquer les rôles.
     */
    protected function performRoleSync(Admin $targetAdmin): void
    {
        $roleIds = array_map('intval', $this->selectedRoles);

        $targetAdmin->syncRoles($roleIds);

        Cache::forget('spatie.permission.cache');
    }


    public function saveRoles(): void
    {
        if (!$this->admin) {
            $this->dispatch('flash-error', message: "Impossible de sauvegarder : L'administrateur n'est pas chargé. Utilisez le mode création.");

            return;
        }

        $this->performRoleSync($this->admin);

        $newCurrentRoleIds = $this->admin->roles->pluck('id')->map('strval')->toArray();
        $this->currentRoles = $newCurrentRoleIds;
        $this->selectedRoles = $newCurrentRoleIds;

        $this->dispatch('flash-success', message: "Les rôles de l'administrateur '{$this->admin->name}' ont été mis à jour.");
    }


    protected function getPermissionsForSelectedRoles(): array
    {
        if (empty($this->selectedRoles)) {
            return [];
        }

        $roleIds = array_map('intval', $this->selectedRoles);

        $permissions = Permission::query()
            ->select('name', 'categorie')
            ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->whereIn('role_has_permissions.role_id', $roleIds)
            ->where('permissions.guard_name', 'admin')
            ->distinct()
            ->get();

        return $permissions->groupBy('categorie')
            ->map(fn ($group) => $group->pluck('name')->toArray())
            ->toArray();
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        $selectedRolesPermissions = $this->getPermissionsForSelectedRoles();

       return view('adminbase::livewire.super-admin.manage-admins.admin-role-selector', [
            'selectedRolesPermissions' => $selectedRolesPermissions,
       ]);
    }
}
