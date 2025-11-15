<div class="space-y-8">
    {{-- GRILLE PRINCIPALE (Adaptation Mobile/Tablet/PC) --}}
    {{-- La grille s'adapte: 1 colonne (mobile), 2 colonnes (sm), 3 colonnes (lg), 4 colonnes (xl) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

        {{-- 1. Total des connexions sur une période (Stat Card Principale) --}}
        {{-- Conservé en col-span-1 --}}
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-2xl dark:shadow-xl dark:shadow-blue-900/20
                    transition duration-300 hover:shadow-blue-500/30 hover:scale-[1.02] cursor-default">
            {{-- ... Contenu inchangé ... --}}
            <div class="flex items-center justify-between">
                <div class="flex-shrink-0 bg-blue-500/10 rounded-xl p-3">
                    <svg class="h-7 w-7 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.5a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.58-7.499-1.632Z" />
                    </svg>
                </div>
                <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Connexions</dt>
            </div>

            <dd class="mt-4 text-5xl font-extrabold text-blue-700 dark:text-blue-300">
                {{ number_format($stats['total_connections']) }}
            </dd>

            <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700/50">
                <h4 class="text-xs font-bold text-gray-600 dark:text-gray-300 mb-2 uppercase tracking-wider">Répartition</h4>
                <div class="space-y-1">
                    @forelse($stats['connections_by_type'] as $typeStat)
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-medium text-gray-500 dark:text-gray-400">{{ ucfirst($typeStat->user_type) }}</span>
                            <span class="font-bold text-gray-900 dark:text-white">{{ number_format($typeStat->count) }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500 dark:text-gray-400">Aucune donnée.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- 2. Utilisateurs distincts --}}
        {{-- Conservé en col-span-1 --}}
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-lg dark:shadow-md dark:shadow-blue-900/10
                    transition duration-300 hover:shadow-blue-500/30 hover:scale-[1.02] cursor-default">
            {{-- ... Contenu inchangé ... --}}
            <div class="flex items-center justify-between">
                <div class="flex-shrink-0 bg-blue-500/10 rounded-xl p-3">
                    <svg class="h-7 w-7 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.944-1.5a4.5 4.5 0 1 0-2.614-2.614zM16.126 7.868a4.5 4.5 0 1 1-8.583-1.045m8.583 1.045L18 17.5" />
                    </svg>
                </div>
                <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Utilisateurs Distincts</dt>
            </div>

            <dd class="mt-4 text-5xl font-extrabold text-blue-700 dark:text-blue-300">
                {{ number_format($stats['distinct_users']) }}
            </dd>

            <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700/50">
                <h4 class="text-xs font-bold text-gray-600 dark:text-gray-300 mb-2 uppercase tracking-wider">Répartition</h4>
                <div class="space-y-1">
                    @forelse($stats['connections_by_type'] as $typeStat)
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-medium text-gray-500 dark:text-gray-400">{{ ucfirst($typeStat->user_type) }}</span>
                            <span class="font-bold text-gray-900 dark:text-white">{{ number_format($typeStat->distinct_users_count) }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500 dark:text-gray-400">Aucune donnée.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- 3. Durée médiane des sessions --}}
        {{-- Conservé en col-span-1 --}}
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-lg dark:shadow-md dark:shadow-purple-900/10
                    transition duration-300 hover:shadow-purple-500/30 hover:scale-[1.02] cursor-default">
            {{-- ... Contenu inchangé ... --}}
            <div class="flex items-center justify-between">
                <div class="flex-shrink-0 bg-purple-500/10 rounded-xl p-3">
                    <svg class="h-7 w-7 text-purple-600 dark:text-purple-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Durée Médiane (Min)</dt>
            </div>

            <dd class="mt-4 text-5xl font-extrabold text-purple-700 dark:text-purple-300">
                {{ number_format($stats['median_duration_by_type']->avg('median_duration'), 2) }}
            </dd>

            <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700/50">
                <h4 class="text-xs font-bold text-gray-600 dark:text-gray-300 mb-2 uppercase tracking-wider">Médiane par Type</h4>
                <div class="space-y-1">
                    @forelse($stats['median_duration_by_type'] as $typeStat)
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-medium text-gray-500 dark:text-gray-400">{{ ucfirst($typeStat->user_type) }}</span>
                            <span class="font-bold text-gray-900 dark:text-white">{{ number_format($typeStat->median_duration, 2) }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500 dark:text-gray-400">Aucune durée médiane.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- 4. Sessions Actives (Mise en évidence) --}}
        {{-- Conservé en col-span-1 --}}
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-2xl dark:shadow-xl dark:shadow-red-900/30
                    transition duration-300 hover:shadow-red-500/50 hover:scale-[1.02] cursor-default">
            {{-- ... Contenu inchangé ... --}}
            <div class="flex items-center justify-between">
                <div class="flex-shrink-0 bg-red-500/10 rounded-xl p-3 ">
                    <svg class="h-7 w-7 text-red-600 dark:text-red-400 animate-pulse" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.83 5.23m7.734-2.834A12 12 0 0 1 12 21h0" />
                    </svg>
                </div>
                <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Sessions Actives <span class="text-red-500 text-xs">(5 min)</span></dt>
            </div>

            <dd class="mt-4 text-5xl font-extrabold text-red-700 dark:text-red-300">
                {{ number_format($stats['active_sessions_by_type']->sum('count')) }}
            </dd>

            <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700/50">
                <h4 class="text-xs font-bold text-gray-600 dark:text-gray-300 mb-2 uppercase tracking-wider">Actives par Type</h4>
                <div class="space-y-1">
                    @forelse($stats['active_sessions_by_type'] as $typeStat)
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-medium text-gray-500 dark:text-gray-400">{{ ucfirst($typeStat->user_type) }}</span>
                            <span class="font-bold text-gray-900 dark:text-white">{{ number_format($typeStat->count) }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500 dark:text-gray-400">Aucune session active récente.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- 5. Top des emplacements (Liste d'anomalie / focus) --}}
        {{-- Modifié: sm:col-span-2 pour tablette (inchangé) et xl:col-span-2 pour PC (prend 2 des 4 colonnes) --}}
        <div class="col-span-1 sm:col-span-2 **xl:col-span-2** bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-lg dark:shadow-md dark:shadow-blue-900/10
                    transition duration-300 hover:shadow-blue-500/30 hover:scale-[1.02] h-full flex flex-col">

            <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                <svg class="h-5 w-5 text-blue-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
                Top 5 Emplacements
            </h3>

            <div class="space-y-3 flex-grow">
                @forelse($stats['top_locations'] as $index => $locationStat)
                    <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700/50 pb-2 last:border-b-0">
                        <div class="flex items-center">
                            {{-- Indicateur de classement stylisé --}}
                            <span class="text-xs font-extrabold w-6 h-6 flex items-center justify-center rounded-full mr-3
                                         {{ $index === 0 ? 'bg-amber-400 text-gray-900 shadow-md' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300' }}">
                                {{ $index + 1 }}
                            </span>
                            <span class="text-sm text-gray-600 dark:text-gray-400 font-medium truncate">
                                {{ $locationStat->location ?? 'Non défini' }}
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="block text-sm text-gray-900 dark:text-white font-bold">{{ number_format($locationStat->count) }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-sm italic py-4">
                        <span class="text-blue-500 font-bold mr-1">•</span>Aucune donnée de localisation enregistrée pour la période.
                    </p>
                @endforelse
            </div>
        </div>
    </div>
</div>
