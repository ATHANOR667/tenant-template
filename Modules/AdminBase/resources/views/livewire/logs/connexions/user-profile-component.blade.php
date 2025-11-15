<div class="space-y-10">

    {{-- 1. Section du Profil (Card principale) --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl dark:shadow-xl dark:shadow-blue-900/10 p-8
                flex flex-col sm:flex-row items-center sm:items-start space-y-6 sm:space-y-0 sm:space-x-8 border border-gray-100 dark:border-gray-700/50">

        {{-- Avatar --}}
        <div class="flex-shrink-0 relative">
            <div class="h-28 w-28 rounded-full bg-blue-500/10 dark:bg-blue-900/30 flex items-center justify-center
                        text-blue-600 dark:text-blue-400 font-extrabold text-5xl shadow-inner dark:shadow-none
                        border-4 border-white dark:border-gray-800"
            >
                {{ strtoupper(substr($user->prenom ?? '?', 0, 1) . substr($user->nom ?? '?', 0, 1)) }}
            </div>
            {{-- Badge de statut (Ex: en ligne ou type d'utilisateur) --}}
            <span class="absolute bottom-0 right-0 block h-5 w-5 rounded-full ring-4 ring-white dark:ring-gray-800
                          {{ $stats['active_sessions'] > 0 ? 'bg-blue-500' : 'bg-gray-400' }}"
                  title="{{ $stats['active_sessions'] > 0 ? 'Actif' : 'Inactif' }}">
            </span>
        </div>

        {{-- Informations du profil --}}
        <div class="text-center sm:text-left flex-grow">
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white whitespace-break-spaces">
                {{ $user->prenom }} {{ $user->nom }}
            </h2>
            <p class="text-xl font-semibold text-blue-700 dark:text-blue-400 mt-1 mb-3">
                {{ ucfirst($user->user_type) }}
            </p>

            <div class="space-y-2 text-gray-600 dark:text-gray-400">
                {{-- Email --}}
                <div class="flex items-center justify-center sm:justify-start text-base">
                    <svg class="h-5 w-5 mr-2 text-gray-500 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    <span class="font-medium truncate">{{ $user->email }}</span>
                </div>
                {{-- Téléphone --}}
                <div class="flex items-center justify-center sm:justify-start text-base">
                    <svg class="h-5 w-5 mr-2 text-gray-500 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    <span class="font-medium">{{ $user->telephone ?? 'N/A' }}</span>
                </div>
                {{-- Dernière activité --}}
                <div class="flex items-center justify-center sm:justify-start text-base pt-3 border-t dark:border-gray-700/50">
                    <svg class="h-5 w-5 mr-2 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    <span class="font-medium text-gray-900 dark:text-white">Dernière activité :</span>
                    <span class="ml-2 font-bold">{{ $stats['last_activity'] }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Statistiques Détaillées (Grille de Cards) --}}
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6">

        {{-- Card Stat Unique : Sessions totales --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 text-center transition duration-300 hover:shadow-blue-500/20">
            <div class="bg-blue-500/10 rounded-full inline-block p-2 mb-3">
                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75" /></svg>
            </div>
            <p class="text-3xl font-extrabold text-blue-700 dark:text-blue-300">{{ $stats['total_sessions'] }}</p>
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mt-1 uppercase tracking-wide">Sessions totales</h3>
        </div>

        {{-- Card Stat Unique : Sessions actives --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 text-center transition duration-300 hover:shadow-blue-500/20">
            <div class="bg-blue-500/10 rounded-full inline-block p-2 mb-3">
                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400 animate-pulse" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5h-6m6 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 10.5a7.5 7.5 0 1 1 15 0 7.5 7.5 0 0 1-15 0Z" /></svg>
            </div>
            <p class="text-3xl font-extrabold text-blue-700 dark:text-blue-300">{{ $stats['active_sessions'] }}</p>
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mt-1 uppercase tracking-wide">Sessions Actives</h3>
        </div>

        {{-- Card Stat Unique : Durée médiane --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 text-center transition duration-300 hover:shadow-blue-500/20">
            <div class="bg-blue-500/10 rounded-full inline-block p-2 mb-3">
                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.5a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.58-7.499-1.632Z" /></svg>
            </div>
            <p class="text-3xl font-extrabold text-blue-700 dark:text-blue-300">
                <span class="text-2xl">{{ $stats['median_session_duration'] }}</span>
            </p>
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mt-1 uppercase tracking-wide">Durée Médiane</h3>
        </div>

        {{-- Card Stat Unique : IPs uniques --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 text-center transition duration-300 hover:shadow-orange-500/20">
            <div class="bg-orange-500/10 rounded-full inline-block p-2 mb-3">
                <svg class="h-6 w-6 text-orange-600 dark:text-orange-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.006a3 3 0 0 1-.398 1.585L7.5 21 3.313 17.684A3 3 0 0 1 3 16.25V6.472c0-1.42.42-2.822 1.24-3.978A7.5 7.5 0 0 1 12 3c2.755 0 5.455.753 7.64 2.062A7.5 7.5 0 0 1 21 16.25v.25M15 12h.01M12 12h.01M9 12h.01M12 7.5h.01M15 7.5h.01M9 7.5h.01" /></svg>
            </div>
            <p class="text-3xl font-extrabold text-orange-700 dark:text-orange-300">{{ $stats['unique_ips'] }}</p>
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mt-1 uppercase tracking-wide">Adresses IP Uniques</h3>
        </div>

        {{-- Autres Stats (sur une ligne dédiée ou pas affichées si trop peu d'infos) --}}
        <div class="col-span-2 md:col-span-4 grid grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Card Stat Unique : Appareils connectés --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 text-center transition duration-300 hover:shadow-purple-500/20">
                <div class="bg-purple-500/10 rounded-full inline-block p-2 mb-3">
                    <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75A2.25 2.25 0 0 0 15.75 1.5H13.5m-3 0V3h3V1.5m-3 0h3" /></svg>
                </div>
                <p class="text-3xl font-extrabold text-purple-700 dark:text-purple-300">{{ $stats['total_devices'] }}</p>
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mt-1 uppercase tracking-wide">Appareils</h3>
            </div>

            {{-- Card Stat Unique : Locations uniques --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 text-center transition duration-300 hover:shadow-red-500/20">
                <div class="bg-red-500/10 rounded-full inline-block p-2 mb-3">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                </div>
                <p class="text-3xl font-extrabold text-red-700 dark:text-red-300">{{ $stats['unique_locations'] }}</p>
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mt-1 uppercase tracking-wide">Locations Uniques</h3>
            </div>

            {{-- Card Stat Unique : Dernière connexion --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 text-center col-span-2 transition duration-300 hover:shadow-gray-500/20 md:col-span-2 lg:col-span-2">
                <div class="bg-gray-500/10 rounded-full inline-block p-2 mb-3">
                    <svg class="h-6 w-6 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008Z" /></svg>
                </div>
                <p class="text-xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['last_connection'] }}</p>
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mt-1 uppercase tracking-wide">Dernière Connexion</h3>
            </div>
        </div>
    </div>

    {{-- 3. Historique des connexions (Tableau et Cartes) --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-gray-700/50">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b dark:border-gray-700/50 pb-4">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-0">Historique des Sessions</h3>
            <button wire:click="exportCsv"
                    class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-300 flex items-center text-sm shadow">
                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Exporter CSV
            </button>
        </div>

        {{-- Tableau pour les grands écrans (Masqué sur mobile, affiché sur PC) --}}
        <div class="overflow-x-auto hidden md:block">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 rounded-xl overflow-hidden">
                <thead class="bg-gray-50 dark:bg-gray-700 border-b-2 border-blue-500">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        Adresse IP / Localisation
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        Appareil
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        Début Session
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        Fin / Durée
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        Activité
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($connectionLogs as $log)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $log->ip_address }}
                            <span class="block text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $log->location ?? 'Localisation inconnue' }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                            {{ Str::limit($log->device_info, 40) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-medium">
                            {{ $log->session_start->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($log->session_end)
                                <span class="text-sm text-gray-500 dark:text-gray-400 block">{{ $log->session_end->format('d/m/Y H:i') }}</span>
                                <span class="text-xs text-blue-600 dark:text-blue-400 font-medium">Durée: {{ $log->session_start->diffForHumans($log->session_end, ['parts' => 2, 'join' => true, 'short' => true]) }}</span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 animate-pulse">
                                    En Cours
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $log->last_activity ? $log->last_activity->format('d/m/Y H:i') : 'N/A' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-base text-gray-500 dark:text-gray-400 italic">
                            <p>Aucun historique de connexion trouvé pour cet utilisateur.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Cartes pour les petits écrans (Affichées sur mobile, masquées sur PC) --}}
        <div class="block md:hidden space-y-4">
            @forelse ($connectionLogs as $log)
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl shadow-md border-l-4 border-blue-500">
                    <div class="flex flex-col space-y-2 text-sm">
                        <div class="flex justify-between items-center border-b pb-1 dark:border-gray-600">
                            <span class="font-bold text-gray-900 dark:text-white">IP / Localisation</span>
                            <span class="text-gray-700 dark:text-gray-300 text-right">{{ $log->ip_address }} ({{ $log->location ?? 'N/A' }})</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-600 dark:text-gray-400">Appareil</span>
                            <span class="text-gray-700 dark:text-gray-300 text-right">{{ Str::limit($log->device_info, 30) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-600 dark:text-gray-400">Début Session</span>
                            <span class="text-gray-700 dark:text-gray-300 text-right">{{ $log->session_start->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-600 dark:text-gray-400">Fin Session</span>
                            <span class="text-right">
                                @if ($log->session_end)
                                    <span class="text-gray-700 dark:text-gray-300">{{ $log->session_end->format('d/m/Y H:i') }}</span>
                                    <span class="block text-xs text-blue-600 dark:text-blue-400">Durée: {{ $log->session_start->diffForHumans($log->session_end, ['parts' => 2, 'join' => true, 'short' => true]) }}</span>
                                @else
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-500 text-white animate-pulse">En Cours</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-600 dark:text-gray-400">Dernière activité</span>
                            <span class="text-gray-700 dark:text-gray-300 text-right">{{ $log->last_activity ? $log->last_activity->format('d/m/Y H:i') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                    Aucun historique de connexion trouvé pour cet utilisateur.
                </div>
            @endforelse
        </div>

        <div class="mt-8 flex justify-center">
            {{ $connectionLogs->links() }}
        </div>
    </div>
</div>
