<?php

namespace Modules\AdminBase\Livewire\Logs\Connexions;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\AdminBase\Models\UserConnectionLog;

class UserConnectionList extends Component
{
    use WithPagination;

    // Filtres actifs
    public array $filters = [];

    // Page actuelle, utilisée pour la pagination manuelle
    public $page = 1;

    // Options pour le filtre de période
    public $periodOptions = [
        'today' => 'Aujourd\'hui',
        'last_7_days' => '7 derniers jours',
        'last_30_days' => '30 derniers jours',
        'last_90_days' => '90 derniers jours',
        'all_time' => 'Tout le temps',
    ];

    /**
     * Conserve les filtres et la page dans l'URL.
     */
    protected $queryString = [
        'filters' => ['except' => []],
        'page' => ['except' => 1],
    ];

    /**
     * Initialisation du composant.
     */
    public function mount(array $filters): void
    {
        $this->filters = $filters;
        // S'assurer que $page est synchronisée avec le query string
        $this->page = request()->query('page', 1);
    }

    /**
     * Écoute l'événement de mise à jour des filtres et réinitialise la pagination.
     */
    #[On('filtersUpdate')]
    public function filtersUpdate(array $filters): void
    {
        $this->filters = $filters;
        // Réinitialise la pagination à la première page lors du changement de filtre
        $this->resetPage();
    }


    /**
     * Sélectionne un utilisateur et émet un événement.
     */
    public function selectUser(string $userId, string $userType): void
    {
        $this->dispatch('userSelected', userId: $userId, userType: $userType);
    }

    /**
     * Rendu de la vue.
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        $perPage = 12; // Augmentation du nombre d'éléments pour une meilleure densité d'affichage

        // 1. Requête SQL pour obtenir les données de connexion agrégées
        $logsQuery = UserConnectionLog::query()
            ->select('user_id', 'user_type', DB::raw('count(*) as connections_count'), DB::raw('MAX(last_activity) as last_activity_date'))
            ->groupBy('user_id', 'user_type')
            ->orderByDesc('last_activity_date'); // Trier par activité récente avant la pagination

        // Applique les filtres
        $logsQuery->forPeriod($this->filters['period'] ?? 'last_7_days')
            ->byUserType($this->filters['user_type'] ?? '')
            ->byLocationOrIp($this->filters['location_or_ip'] ?? '');

        if (($this->filters['activity_status'] ?? '') === 'active') {
            $logsQuery->activeSessions();
        }

        // Récupérer TOUS les logs AGRÉGÉS (NÉCESSAIRE pour paginer manuellement APRES hydratation)
        $logsData = $logsQuery->get();



        $users = collect();

        // on récupère tous les logs groupés par type d’utilisateur
        $logsByType = $logsData->groupBy('user_type');

        // On précharge toutes les IP uniques par utilisateur en une seule requête
        $uniqueIps = UserConnectionLog::select('user_id', 'user_type')
            ->selectRaw('COUNT(DISTINCT ip_address) as unique_ips_count')
            ->groupBy('user_id', 'user_type')
            ->get()
            ->keyBy(fn($row) => $row->user_type . '-' . $row->user_id);

        // Boucle sur les types d’utilisateurs enregistrés dans le morphMap
        foreach (Relation::morphMap() as $alias => $model) {
            if (!isset($logsByType[$alias])) {
                continue;
            }

            $typeLogs = $logsByType[$alias];
            $userIds = $typeLogs->pluck('user_id')->unique();

            // On récupère tous les utilisateurs d’un coup
            $model::whereIn('id', $userIds)->get()->each(function ($user) use ($typeLogs, $alias, $uniqueIps, $users) {
                $logData = $typeLogs->firstWhere('user_id', $user->id);

                if ($logData) {
                    $user->connections_count = $logData->connections_count ?? 0;
                    $user->last_activity_date = $logData->last_activity_date
                        ? Carbon::parse($logData->last_activity_date)
                        : null;
                    $user->user_type = $alias;

                    // Lecture des IP uniques depuis la collection préchargée
                    $key = $alias . '-' . $user->id;
                    $user->unique_ips_count = $uniqueIps[$key]->unique_ips_count ?? 0;

                    $users->push($user);
                }
            });
        }


        // Application de la recherche et du tri sur la collection hydratée
        if (!empty($this->filters['search'])) {
            $search = strtolower($this->filters['search']);
            $users = $users->filter(function ($user) use ($search) {
                // S'assurer que les propriétés existent pour éviter les erreurs
                return (isset($user->nom) && str_contains(strtolower($user->nom), $search)) ||
                    (isset($user->prenom) && str_contains(strtolower($user->prenom), $search)) ||
                    (isset($user->matricule) && str_contains(strtolower($user->matricule), $search)) ||
                    (isset($user->email) && str_contains(strtolower($user->email), $search));
            });
        }

        // Tri final par last_activity_date (pour s'assurer du tri après hydratation/filtre)
        $users = $users->sortByDesc('last_activity_date')->values();

        // Pagination manuelle de la collection hydratée (LengthAwarePaginator)
        $paginatedUsers = new LengthAwarePaginator(
            $users->forPage($this->page, $perPage),
            $users->count(),
            $perPage,
            $this->page,
            // Conserver les autres paramètres de l'URL pour les liens de pagination
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('adminbase::livewire.logs.connexions.user-connection-list', [
            'users' => $paginatedUsers,
            'userTypes' => array_keys(Relation::morphMap()),
            'periodOptions' => $this->periodOptions,
        ]);
    }
}
