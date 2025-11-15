<?php

namespace Modules\AdminBase\Livewire\Logs\Connexions;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\AdminBase\Models\UserConnectionLog;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserProfileComponent extends Component
{
    use WithPagination;

    public $user;
    public $stats;
    protected $paginationTheme = 'tailwind';

    public function mount($user): void
    {
        $this->user = $user;
        $this->calculateStats();
    }

    /**
     * Calcule et charge les statistiques de l'utilisateur de manière optimisée.
     */
    public function calculateStats(): void
    {
        $morphAlias = Relation::getMorphAlias(get_class($this->user));

        $baseQuery = UserConnectionLog::where('user_id', $this->user->id)
            ->where('user_type', $morphAlias);

        // Sessions totales (inclut ouvertes et fermées)
        $totalSessions = (clone $baseQuery)->count();

        // Sessions actives (session_end null et last_activity récente)
        $activeSessions = (clone $baseQuery)
            ->whereNull('session_end')
            ->where('last_activity', '>=', now()->subMinutes(5))
            ->count();

        // Médiane de la durée des sessions fermées (en minutes, via SQL pour optimisation)
        $medianDuration = (clone $baseQuery)
            ->whereNotNull('session_end')
            ->whereRaw('EXTRACT(EPOCH FROM (session_end - session_start)) > 0')
            ->selectRaw('PERCENTILE_CONT(0.5) WITHIN GROUP (ORDER BY EXTRACT(EPOCH FROM (session_end - session_start))) / 60 AS median_duration')
            ->value('median_duration') ?? 0;

        // Dernière activité (max last_activity)
        $lastActivity = (clone $baseQuery)->max('last_activity');

        // Dernière connexion (max session_start)
        $lastConnection = (clone $baseQuery)->max('session_start');

        // Appareils distincts
        $totalDevices = (clone $baseQuery)->distinct('device_info')->count('device_info');

        // IPs uniques
        $uniqueIps = (clone $baseQuery)->distinct('ip_address')->count('ip_address');

        // Locations uniques
        $uniqueLocations = (clone $baseQuery)->whereNotNull('location')->distinct('location')->count('location');

        $this->stats = [
            'total_sessions' => $totalSessions,
            'active_sessions' => $activeSessions,
            'median_session_duration' => ($medianDuration > 0)
                ? CarbonInterval::minutes((int)$medianDuration)->cascade()->forHumans()
                : 'N/A',
            'total_devices' => $totalDevices,
            'unique_ips' => $uniqueIps,
            'unique_locations' => $uniqueLocations,
            'last_activity' => $lastActivity ? Carbon::parse($lastActivity)->diffForHumans() : 'N/A',
            'last_connection' => $lastConnection ? Carbon::parse($lastConnection)->diffForHumans() : 'N/A',
        ];
    }

    /**
     * Exporte l'historique des connexions en CSV.
     */
    public function exportCsv(): StreamedResponse
    {
        $morphAlias = Relation::getMorphAlias(get_class($this->user));

        // CHANGEMENT: Personnalisation du nom du fichier et de l'en-tête selon le type d'utilisateur
        $filename = $morphAlias === 'super-admin'
            ? "historique_connexions_super-admin_{$this->user->id}.csv"
            : "historique_connexions_" . str_replace(' ', '_', $this->user->prenom) . "_" . str_replace(' ', '_', $this->user->nom) . "_$morphAlias._.{$this->user->id}.csv";

        $header = $morphAlias === 'super-admin'
            ? ['Historique des connexions pour Super Admin']
            : ["Historique des connexions pour {$this->user->prenom} {$this->user->nom} (" . ucfirst($morphAlias) . ")"];

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = ['Adresse IP', 'Localisation', 'Appareil', 'Début de session', 'Fin de session', 'Dernière activité'];

        $callback = function () use ($morphAlias, $header, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $header); // Ajoute l'en-tête personnalisée
            fputcsv($file, []); // Ligne vide pour séparation
            fputcsv($file, $columns); // En-têtes des colonnes

            UserConnectionLog::where('user_id', $this->user->id)
                ->where('user_type', $morphAlias)
                ->latest('session_start')
                ->chunk(100, function ($logs) use ($file) {
                    foreach ($logs as $log) {
                        fputcsv($file, [
                            $log->ip_address,
                            $log->location ?? 'N/A',
                            $log->device_info ?? 'N/A',
                            $log->session_start->format('d/m/Y H:i:s'),
                            $log->session_end ? $log->session_end->format('d/m/Y H:i:s') : 'En cours',
                            $log->last_activity ? $log->last_activity->format('d/m/Y H:i:s') : 'N/A',
                        ]);
                    }
                });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        $connectionLogs = UserConnectionLog::where('user_id', $this->user->id)
            ->where('user_type', Relation::getMorphAlias(get_class($this->user)))
            ->latest('session_start')
            ->paginate(10);

        return view('adminbase::livewire.logs.connexions.user-profile-component', [
            'connectionLogs' => $connectionLogs,
        ]);
    }
}
