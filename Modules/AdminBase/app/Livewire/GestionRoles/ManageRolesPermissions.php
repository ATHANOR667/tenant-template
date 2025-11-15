<?php

namespace Modules\AdminBase\Livewire\GestionRoles;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ManageRolesPermissions extends Component
{
    public string $newRoleName = '';
    public ?int $selectedRoleId = null;
    public ?Role $selectedRole = null;
    public array $rolePermissions = [];
    public string $guardName;

    public Collection $roles;
    public Collection $permissions;

    protected function rules(): array
    {
        return [
            'newRoleName' => [
                'required',
                'string',
                'min:3',
                Rule::unique('roles', 'name')->where(fn ($query) => $query->where('guard_name', $this->guardName)),
            ],
            'selectedRoleId' => 'nullable|exists:roles,id',
            'rolePermissions' => 'nullable|array',
            'rolePermissions.*' => 'exists:permissions,id',
        ];
    }

    protected array $messages = [
        'newRoleName.required' => 'Le nom du rôle est obligatoire.',
        'newRoleName.min' => 'Le nom du rôle doit contenir au moins :min caractères.',
        'newRoleName.unique' => 'Ce nom de rôle existe déjà pour ce guard.',
        'rolePermissions.*.exists' => 'Une permission sélectionnée n\'est pas valide.'
    ];

    /**
     * Regroupe les permissions par 'categorie' pour l'affichage.
     * @return Collection<string, Collection<Permission>>
     */
    public function getGroupedPermissionsProperty(): Collection
    {
        // Regroupement par 'categorie' et tri personnalisé
        return $this->permissions
            ->groupBy('categorie')
            ->sortBy(fn ($permissions, $categorie) => match ($categorie) {
                'super-admin' => 0,
                'admin' => 1,
                default => 2,
            });
    }

    public function mount(): void
    {
        $this->roles = new Collection();
        $this->permissions = new Collection();
        $this->loadRolesAndPermissions();
    }

    public function loadRolesAndPermissions(): void
    {
        $this->roles = Role::where('guard_name', $this->guardName)->orderBy('name')->get();
        // Tri des permissions par 'categorie' puis 'name'
        $this->permissions = Permission::where('guard_name', $this->guardName)
            ->orderBy('categorie')->orderBy('name')->get();

        if ($this->selectedRoleId) {
            $this->selectedRole = Role::findById($this->selectedRoleId, $this->guardName);

            if ($this->selectedRole) {
                $this->rolePermissions = $this->selectedRole->permissions->pluck('id')->toArray();
            } else {
                $this->selectedRoleId = null;
                $this->rolePermissions = [];
                $this->dispatch('flash-error', message: "Le rôle précédemment sélectionné n'est plus disponible pour le guard '{$this->guardName}'.");
            }
        } else {
            $this->selectedRole = null;
            $this->rolePermissions = [];
        }
    }

    // --- MÉTHODES D'ACTION ---

    public function createRole(): void
    {
        $this->validateOnly('newRoleName');

        try {
            Role::create(['name' => $this->newRoleName, 'guard_name' => $this->guardName]);

            $message = 'Rôle "' . $this->newRoleName . '" créé avec succès.' ;
            $this->dispatch('flash-success', message: $message);

            $this->reset('newRoleName');
            $this->loadRolesAndPermissions();
            $this->dispatch('dataUpdate');

        } catch (\Exception $e) {
            $message = 'Erreur lors de la création du rôle: ' . $e->getMessage();
            $this->dispatch('flash-error', message: $message);
            Log::error('ManageRolesPermissions - Erreur createRole: ' . $e->getMessage(), ['exception' => $e]);
        }
    }

    public function selectRole(int $roleId): void
    {
        // Toggle la désélection si on clique sur le rôle actif
        if ($this->selectedRoleId === $roleId) {
            $this->reset(['selectedRoleId', 'selectedRole', 'rolePermissions']);
            $this->dispatch('roleDeselected');
            return;
        }

        $this->selectedRoleId = $roleId;
        $this->selectedRole = Role::findById($roleId, $this->guardName);

        if ($this->selectedRole) {
            $this->rolePermissions = $this->selectedRole->permissions->pluck('id')->toArray();
            $this->dispatch('roleSelected'); // Déclencher la mise à jour Alpine.js
        } else {
            $this->selectedRoleId = null;
            $this->rolePermissions = [];
            $this->dispatch('flash-error', message: "Le rôle avec l'ID {$roleId} n'a pas été trouvé pour le guard '{$this->guardName}'.");
        }
    }

    public function updateRolePermissions(): void
    {
        if (!$this->selectedRole) {
            $this->dispatch('flash-error', message: 'Aucun rôle sélectionné pour mettre à jour les permissions.');
            return;
        }

        $this->validate([
            'rolePermissions' => 'nullable|array',
            'rolePermissions.*' => 'exists:permissions,id',
        ]);

        try {
            $permissionsToSync = Permission::whereIn('id', $this->rolePermissions)
                ->where('guard_name', $this->guardName)
                ->get();

            $this->selectedRole->syncPermissions($permissionsToSync);

            $message = 'Permissions mises à jour pour le rôle "' . $this->selectedRole->name . '".';
            $this->dispatch('flash-success', message: $message);

            // Recharger l'état du rôle et des permissions
            $this->selectedRole = $this->selectedRole->fresh();
            $this->rolePermissions = $this->selectedRole->permissions->pluck('id')->toArray();

            $this->dispatch('dataUpdate'); // Pour mettre à jour l'état initial dans Alpine.js

        } catch (\Exception $e) {
            $message = 'Erreur lors de la mise à jour des permissions: ' . $e->getMessage();
            $this->dispatch('flash-error', message: $message);
            Log::error('ManageRolesPermissions - Erreur updateRolePermissions: ' . $e->getMessage(), ['exception' => $e]);
        }
    }

    public function deleteRole(int $roleId): void
    {
        try {
            $roleToDelete = Role::findById($roleId, $this->guardName);

            if ($roleToDelete) {
                // Protéger les rôles sensibles
                if (in_array($roleToDelete->name, ['super-admin', 'admin'])) {
                    $this->dispatch('flash-error', message: 'Ce rôle est protégé et ne peut pas être supprimé.');
                    return;
                }

                $roleName = $roleToDelete->name;
                $roleToDelete->delete();
                $message = 'Rôle "' . $roleName . '" supprimé avec succès.';
                $this->dispatch('flash-success', message: $message);

                if ($this->selectedRoleId === $roleId) {
                    $this->reset(['selectedRoleId', 'selectedRole', 'rolePermissions']);
                }
            } else {
                $message = "Le rôle avec l'ID {$roleId} n'a pas été trouvé ou n'appartient pas au guard '{$this->guardName}'.";
                $this->dispatch('flash-error', message: $message);
            }

            $this->loadRolesAndPermissions();
            $this->dispatch('dataUpdate');
        } catch (\Exception $e) {
            $message = 'Erreur lors de la suppression du rôle: ' . $e->getMessage();
            $this->dispatch('flash-error', message: $message);
            Log::error('ManageRolesPermissions - Erreur deleteRole: ' . $e->getMessage(), ['exception' => $e]);
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory
    {
        return view('adminbase::livewire.gestion-roles.manage-roles-permissions');
    }
}
