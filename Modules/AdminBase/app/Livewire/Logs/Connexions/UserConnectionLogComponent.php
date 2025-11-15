<?php

namespace Modules\AdminBase\Livewire\Logs\Connexions;

use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class UserConnectionLogComponent extends Component
{
    use WithPagination;

    /** État de la vue : 'global' ou 'user-profile'  */
    public $currentView = 'global';

    public $selectedUser = null;

    public $filters = [
        'user_type' => '',
        'search' => '',
        'period' => 'last_7_days',
        'activity_status' => '',
        'location_or_ip' => '',
    ];

    public $periodOptions = [
        'today' => 'Aujourd\'hui',
        'last_7_days' => '7 derniers jours',
        'last_30_days' => '30 derniers jours',
        'last_90_days' => '90 derniers jours',
        'all_time' => 'Tout le temps',
    ];


    /**
     * Gère la sélection d'un utilisateur depuis un composant enfant.
     *
     * Entrainant un passage à la vue du profil
     */
    #[On("userSelected")]
    public function userSelected( $userId , $userType): void
    {
        $user = Relation::getMorphedModel($userType)::find($userId);
        $this->selectedUser = $user ;
        $this->currentView = 'user-profile';
    }

    /**
     * Retourne à la vue globale.
     */
    public function backToGlobal(): void
    {
        $this->currentView = 'global';
        $this->selectedUser = null;
    }

    /** Diffuse l'évent de mise à jour des filtres aux composants enfants */
    public function applyFilters(): void
    {
        $this->dispatch('filtersUpdate' , filters : $this->filters);
    }

    /** Retirer les filtres   */
    public function removeFilter($key): void
    {
        if (array_key_exists($key, $this->filters)) {
            $this->filters[$key] = $key === 'period' ? 'last_7_days' : '';
        }
        $this->applyFilters();
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('adminbase::livewire.logs.connexions.user-connection-log-component', [
            'filters' => $this->filters,
            'periodOptions' => $this->periodOptions,
            'userTypes' => array_keys(Relation::morphMap()),
        ]);
    }
}
