<?php

namespace Modules\AdminBase\Livewire\Logs\Connexions;

use Modules\AdminBase\Models\UserConnectionLog;
use Livewire\Attributes\On;
use Livewire\Component;

class UserConnectionStats extends Component
{
    public array $stats ;
    public array $filters ;



    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('adminbase::livewire.logs.connexions.user-connection-stats');
    }

    public function mount($filters ): void
    {
        $this->filters = $filters;
        $this->loadStats();
    }
    #[On('filtersUpdate')]
    public function filtersUpdate($filters): void
    {
        $this->filters = $filters ;
        $this->loadStats();
    }

    public function loadStats(): void
    {
        // Utilise scopes pour tous les filtres
        $baseQuery = UserConnectionLog::query()
            ->forPeriod($this->filters['period'] ?? 'last_7_days')
            ->byUserType($this->filters['user_type'] ?? '')
            ->byLocationOrIp($this->filters['location_or_ip'] ?? '');

        if ($this->filters['activity_status'] === 'active') {
            $baseQuery->activeSessionsRecentlyUsed();
        }

        // Total connections
        $total = (clone $baseQuery)->count();

        // Distinct users
        $distinct = (clone $baseQuery)->distinct('user_id')->count('user_id');

        // Connections by type
        $connectionsByType = (clone $baseQuery)
            ->select('user_type')
            ->selectRaw('COUNT(*) AS count')
            ->selectRaw('COUNT(DISTINCT user_id) AS distinct_users_count')
            ->groupBy('user_type')
            ->get();

        // Top locations
        $topLocations = (clone $baseQuery)
            ->selectRaw('location, COUNT(*) as count')
            ->whereNotNull('location')
            ->groupBy('location')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Sessions actives groupées par user_type
        $activeSessionsByType = UserConnectionLog::query()
            ->activeSessionsRecentlyUsed()
            ->select('user_type')
            ->selectRaw('COUNT(*) AS count')
            ->groupBy('user_type')
            ->get();


        //  Médiane de la durée des sessions groupée par user_type (utilise PERCENTILE_CONT pour PostgreSQL)
        $medianDurationByType = (clone $baseQuery)
            ->select('user_type')
            ->selectRaw('PERCENTILE_CONT(0.5) WITHIN GROUP (ORDER BY EXTRACT(EPOCH FROM (last_activity - session_start))) / 60 AS median_duration')
            ->groupBy('user_type')
            ->get();

        $this->stats = [
            'total_connections' => $total,
            'distinct_users' => $distinct,
            'connections_by_type' => $connectionsByType,
            'top_locations' => $topLocations,
            'active_sessions_by_type' => $activeSessionsByType,
            'median_duration_by_type' => $medianDurationByType,
        ];
    }




}
