<?php

namespace Modules\AdminBase\Livewire\SuperAdmin\ManageAdmins;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\AdminBase\Models\Admin; // Assurez-vous que ce chemin est correct
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\Permission\Models\Role;

class AdminProfileCard extends Component
{
    use WithFileUploads;

    public Admin|null $admin = null;

    // Champs du formulaire
    public string $nom = '';
    public string $prenom = '';
    public string|null $email = '';
    public string $telephone = '';
    public ?string $pays = null;
    public ?string $ville = null;

    // Fichiers temporaires pour l'upload
    public $newPhotoProfil;
    public $newPieceIdentiteRecto;
    public $newPieceIdentiteVerso;

    // Indicateurs de suppression de fichiers existants
    public bool $deletePhotoProfil = false;
    public bool $deletePieceIdentiteRecto = false;
    public bool $deletePieceIdentiteVerso = false;

    public array $selectedRoles = [];

    // État de l'UI
    public bool $showModal = false;
    public bool $showPieceIdentiteRecto = true;
    public bool $isFlipping = false;

    protected function rules(): array
    {
        $adminId = $this->admin ? $this->admin->id : null;

        return [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                // Validation d'unicité, ignore l'admin actuel et permet NULL
                Rule::unique('admins')->ignore($adminId)->where(fn ($query) => $query->whereNotNull('email')),
            ],
            'telephone' => ['nullable', 'string', 'max:20', Rule::unique('admins', 'telephone')->ignore($adminId)],
            'pays' => ['nullable', 'string', 'max:255'],
            'ville' => ['nullable', 'string', 'max:255'],
            // Validation des fichiers
            'newPhotoProfil' => ['nullable', 'image', 'max:1024'],
            'newPieceIdentiteRecto' => ['nullable', 'image', 'max:2048'],
            'newPieceIdentiteVerso' => ['nullable', 'image', 'max:2048'],
            // Validation des rôles (sera mis à jour par l'enfant via wire:model)
            'selectedRoles' => ['nullable', 'array'],
            'selectedRoles.*' => ['exists:roles,id'],
            // Validation des indicateurs de suppression
            'deletePhotoProfil' => ['boolean'],
            'deletePieceIdentiteRecto' => ['boolean'],
            'deletePieceIdentiteVerso' => ['boolean'],
        ];
    }

    protected array $messages = [
        'email.unique' => 'Cet e-mail est déjà utilisé par un autre administrateur.',
        'telephone.unique' => 'Ce numéro de téléphone est déjà utilisé par un autre administrateur.',
    ];

    public function updated($propertyName): void
    {
        if (in_array($propertyName, ['newPhotoProfil', 'newPieceIdentiteRecto', 'newPieceIdentiteVerso'])) {
            $this->validateOnly($propertyName);
        }
    }

    #[On('openAdminProfileCard')]
    public function openModal(string $adminId): void
    {
        // 1. Charger l'administrateur
        $this->admin = Admin::withTrashed()->find($adminId);

        if (!$this->admin) {
            session()->flash('error', "Administrateur non trouvé.");
            $this->closeModal();
            return;
        }

        // 2. Remplir les propriétés du formulaire
        $this->fill([
            'nom' => $this->admin->nom,
            'prenom' => $this->admin->prenom,
            'email' => $this->admin->email,
            'telephone' => $this->admin->telephone,
            'pays' => $this->admin->pays,
            'ville' => $this->admin->ville,
            // Initialiser $selectedRoles à vide. L'enfant le remplira après l'événement.
            'selectedRoles' => []
        ]);

        // 3. Réinitialisation des propriétés de fichiers/upload
        $this->reset([
            'newPhotoProfil', 'newPieceIdentiteRecto', 'newPieceIdentiteVerso',
            'deletePhotoProfil', 'deletePieceIdentiteRecto', 'deletePieceIdentiteVerso',
            'isFlipping',
        ]);

        // 4. Ouvrir la Modal
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->dispatch('refreshAdminsList');
        $this->showModal = false;
        $this->resetValidation();
        // Réinitialisation de toutes les propriétés pour l'état initial
        $this->reset([
            'admin', 'nom', 'prenom', 'email', 'telephone', 'pays', 'ville',
            'newPhotoProfil', 'newPieceIdentiteRecto', 'newPieceIdentiteVerso',
            'deletePhotoProfil', 'deletePieceIdentiteRecto', 'deletePieceIdentiteVerso',
            'selectedRoles','showPieceIdentiteRecto', 'isFlipping'
        ]);
    }

    public function updateAdmin(): void
    {
        if (!$this->admin) {
            $this->dispatch('flash-error', message: "Aucun administrateur sélectionné pour la mise à jour.");
            return;
        }

        // Valide les données (y compris $selectedRoles qui est synchronisé par l'enfant)
        $this->validate();

        try {
            $adminData = [
                'nom' => $this->nom,
                'prenom' => $this->prenom,
                // Assurer que l'email vide est enregistré comme null
                'email' => !empty($this->email) ? $this->email : null,
                'telephone' => $this->telephone,
                'pays' => $this->pays,
                'ville' => $this->ville,
            ];

            // Gestion des fichiers (Photo Profil, Pièces d'identité)
            $adminData['photoProfil'] = $this->handleFileUpdate(
                'photoProfil', $this->newPhotoProfil, $this->deletePhotoProfil, 'admins/profils'
            );
            $adminData['pieceIdentiteRecto'] = $this->handleFileUpdate(
                'pieceIdentiteRecto', $this->newPieceIdentiteRecto, $this->deletePieceIdentiteRecto, 'admins/pieces'
            );
            $adminData['pieceIdentiteVerso'] = $this->handleFileUpdate(
                'pieceIdentiteVerso', $this->newPieceIdentiteVerso, $this->deletePieceIdentiteVerso, 'admins/pieces'
            );

            $this->admin->update($adminData);

            $this->dispatch('flash-success', message: "Profil de {$this->admin->prenom} {$this->admin->nom} mis à jour avec succès !");
            $this->dispatch('adminUpdated');
            $this->closeModal();

        } catch (\Exception $e) {
            $this->dispatch('flash-error', message: "Erreur lors de la mise à jour du profil: " . $e->getMessage());
            Log::error('AdminProfileCard - Erreur updateAdmin: ' . $e->getMessage(), ['exception' => $e]);
        }
    }

    /**
     * Logique générique pour gérer l'upload, la suppression et la conservation des fichiers.
     */
    protected function handleFileUpdate(string $field, $newFile, bool $delete, string $folder): ?string
    {
        // 1. Suppression demandée
        if ($delete) {
            if ($this->admin->$field && Storage::disk('public')->exists($this->admin->$field)) {
                Storage::disk('public')->delete($this->admin->$field);
            }
            return null;
        }

        // 2. Nouveau fichier uploadé
        if ($newFile) {
            // Supprime l'ancien fichier s'il existe
            if ($this->admin->$field && Storage::disk('public')->exists($this->admin->$field)) {
                Storage::disk('public')->delete($this->admin->$field);
            }
            return $newFile->store($folder, 'public'); // Stocke le nouveau fichier
        }

        // 3. Pas de changement, conserve l'existant
        return $this->admin->$field;
    }

    /**
     * Génère un PDF du profil de l'administrateur.
     */
    public function generatePdf(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        if (!$this->admin) {
            abort(404, "Admin not found for PDF generation.");
        }

        $admin = Admin::with(['roles.permissions'])->find($this->admin->id);

        // Préparation des chemins et du QR Code pour le PDF
        $photoProfilPath = $admin->photoProfil && Storage::disk('public')->exists($admin->photoProfil) ? Storage::disk('public')->path($admin->photoProfil) : null;
        $pieceIdentiteRectoPath = $admin->pieceIdentiteRecto && Storage::disk('public')->exists($admin->pieceIdentiteRecto) ? Storage::disk('public')->path($admin->pieceIdentiteRecto) : null;
        $pieceIdentiteVersoPath = $admin->pieceIdentiteVerso && Storage::disk('public')->exists($admin->pieceIdentiteVerso) ? Storage::disk('public')->path($admin->pieceIdentiteVerso) : null;
        $qrCodeSvg = QrCode::size(150)->generate($admin->matricule)->toHtml();
        $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);

        $pdf = Pdf::loadView('adminbase::pdf.admin_profile', compact('admin', 'photoProfilPath', 'pieceIdentiteRectoPath', 'pieceIdentiteVersoPath', 'qrCodeBase64'));
        $pdf->setPaper('A4', 'portrait');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'fiche_admin_' . $admin->nom.' '.$admin->prenom . '.pdf');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory
    {
        return view('adminbase::livewire.super-admin.manage-admins.admin-profile-card');
    }
}
