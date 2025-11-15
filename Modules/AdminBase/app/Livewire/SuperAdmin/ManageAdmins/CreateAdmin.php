<?php

namespace Modules\AdminBase\Livewire\SuperAdmin\ManageAdmins;

use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\AdminBase\Models\Admin;
use Spatie\Permission\Models\Role;

class CreateAdmin extends Component
{
    use WithFileUploads;

    public string $nom = '';
    public string $prenom = '';
    public string $telephone = '';

    public ?string $email = null;
    public ?string $pays = null;
    public ?string $ville = null;

    public $photoProfil;
    public $pieceIdentiteRecto;
    public $pieceIdentiteVerso;

    public array $selectedRoles = [];
    public bool $showModal = false;

    protected $listeners = ['openCreateAdminModal' => 'openModal'];

    public function getAvailableRolesProperty()
    {
        return Role::where('guard_name', 'admin')->orderBy('name')->get();
    }

    protected function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('admins')->where(fn ($query) => $query->whereNotNull('email')),
            ],

            'telephone' => ['nullable', 'string', 'max:20', Rule::unique('admins', 'telephone')],
            'pays' => ['nullable', 'string', 'max:255'],
            'ville' => ['nullable', 'string', 'max:255'],
            'photoProfil' => ['nullable', 'image', 'max:1024'],
            'pieceIdentiteRecto' => ['nullable', 'image', 'max:2048'],
            'pieceIdentiteVerso' => ['nullable', 'image', 'max:2048'],
            'selectedRoles' => ['nullable', 'array'],
            'selectedRoles.*' => ['exists:roles,id'],
        ];
    }

    protected $messages = [
        'nom.required' => 'Le nom est obligatoire.',
        'prenom.required' => 'Le prénom est obligatoire.',
        'email.email' => 'L\'adresse e-mail doit être une adresse valide.',
        'email.unique' => 'Cet e-mail est déjà utilisé.',
        'telephone.required' => 'Le numéro de téléphone est obligatoire.',
        'telephone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
        'photoProfil.image' => 'Le fichier de la photo de profil doit être une image.',
        'photoProfil.max' => 'La photo de profil ne doit pas dépasser 1MB.',
        'pieceIdentiteRecto.image' => 'Le fichier recto de la pièce d\'identité doit être une image.',
        'pieceIdentiteRecto.max' => 'Le fichier recto de la pièce d\'identité ne doit pas dépasser 2MB.',
        'pieceIdentiteVerso.image' => 'Le fichier verso de la pièce d\'identité doit être une image.',
        'pieceIdentiteVerso.max' => 'Le fichier verso de la pièce d\'identité ne doit pas dépasser 2MB.',
        'selectedRoles.*.exists' => 'Un rôle sélectionné n\'est pas valide.',
    ];


    public function openModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetValidation();
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset([
            'nom', 'prenom', 'email',
            'telephone', 'pays', 'ville',
            'photoProfil', 'pieceIdentiteRecto', 'pieceIdentiteVerso',
            'selectedRoles'
        ]);
        $adminRole = Role::where('name', 'admin')->where('guard_name', 'admin')->first();
        if ($adminRole) {
            $this->selectedRoles = [$adminRole->id];
        }
    }


    public function createAdmin(): void
    {
        $this->validate();

        try {
            // 1. Créez d'abord l'administrateur SANS les fichiers
            $admin = Admin::create([
                'nom' => $this->nom,
                'prenom' => $this->prenom,
                'email' =>  $this->email,
                'telephone' => $this->telephone,
                'pays' => $this->pays,
                'ville' => $this->ville,
            ]);

            // 2. Affectez les fichiers à l'instance du modèle.
            // C'est ici que notre trait HasMedia intervient !
            if ($this->photoProfil) {
                $admin->photoProfil = $this->photoProfil;
            }

            if ($this->pieceIdentiteRecto) {
                $admin->pieceIdentiteRecto = $this->pieceIdentiteRecto;
            }

            if ($this->pieceIdentiteVerso) {
                $admin->pieceIdentiteVerso = $this->pieceIdentiteVerso;
            }

            // 3. Sauvegardez le modèle pour persister les chemins des fichiers
            $admin->save();

            // event écouté par le composant de roles
            $this->dispatch('adminCreated' , adminCreated : $admin->id );


            $message = 'Administrateur "' . $this->prenom . ' ' . $this->nom . '" créé avec succès !';
            $this->dispatch('flash-success', message: $message);
            $this->dispatch('dataUpdate');
            $this->closeModal();

        } catch (\Exception $e) {
            $message = 'Erreur lors de la création de l\'administrateur: ' . $e->getMessage();
            $this->dispatch('flash-error', message: $message);
            Log::error('CreateAdmin - Erreur createAdmin: ' . $e->getMessage(), ['exception' => $e]);
        }
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory
    {
        return view('adminbase::livewire.super-admin.manage-admins.create-admin');
    }
}
