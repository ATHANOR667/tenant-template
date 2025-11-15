<div class="space-y-8">
    {{-- Indicateur de chargement (intégré ci-dessus) --}}

    {{-- 1. Version CARTE (Mobile et Tablette : sm, md) --}}
    <div class="lg:hidden">
        <div wire:loading.class.delay="opacity-50 pointer-events-none"
             class="grid gap-6
                    grid-cols-1   {{-- Mobile: 1 colonne --}}
                    sm:grid-cols-2 {{-- Tablette: 2 colonnes --}}
                    md:grid-cols-3 {{-- Ajout: 3 colonnes sur md avant de passer au tableau --}}
                    ">

            @if ($users->isEmpty())
                {{-- Message d'absence de données (Identique) --}}
                <div class="col-span-full text-center py-20 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-16 h-16 text-blue-400/70 mx-auto mb-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.944-1.5a4.5 4.5 0 1 0-2.614-2.614zM21 12a9 9 0 1 0-2.639 6.36m-2.672-6.69c.677 0 1.25.573 1.25 1.25s-.573 1.25-1.25 1.25-1.25-.573-1.25-1.25.573-1.25 1.25-1.25z"/>
                    </svg>
                    <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">Aucun utilisateur trouvé</p>
                    <p class="text-gray-500 dark:text-gray-400 mt-2">Veuillez vérifier les filtres appliqués ou votre
                        terme de recherche.</p>
                </div>
            @else
                @foreach ($users as $user)
                    @php
                        $morphAlias = \Illuminate\Database\Eloquent\Relations\Relation::getMorphAlias(get_class($user));
                        $isSuspicious = $user->unique_ips_count > 5;
                    @endphp

                    {{-- Carte Utilisateur (Identique à l'original) --}}
                    <div wire:key="user-{{ $user->id }}-{{ $user->user_type }}"
                         wire:click="selectUser('{{ $user->id }}', '{{ $morphAlias }}')"
                         class="bg-white dark:bg-gray-800 rounded-xl shadow-xl border-t-4
                                {{ $isSuspicious ? 'border-red-500 hover:border-red-600' : 'border-blue-500 hover:border-blue-600' }}
                                hover:shadow-2xl transition-all duration-300 p-6 cursor-pointer transform hover:-translate-y-1
                                flex flex-col h-full">

                        {{-- En-tête de la carte (Avatar et Nom) --}}
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="flex-shrink-0">
                                {{-- Avatar Cercle --}}
                                <div class="h-14 w-14 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center
                                            text-blue-600 dark:text-blue-400 font-bold text-2xl border-2 border-blue-300 dark:border-blue-700">
                                    {{ strtoupper(substr($user->prenom ?? '', 0, 1) . substr($user->nom ?? '', 0, 1)) }}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-xl font-extrabold text-gray-900 dark:text-white leading-tight truncate"
                                    title="{{ $user->prenom }} {{ $user->nom }}">
                                    {{ $user->prenom }} {{ $user->nom }}
                                </h3>
                                <p class="text-sm font-semibold
                                          {{ $isSuspicious ? 'text-red-500 dark:text-red-400' : 'text-blue-600 dark:text-blue-400' }}">
                                    {{ ucfirst($morphAlias) }}
                                </p>
                            </div>
                        </div>

                        {{-- Séparateur --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 my-3"></div>

                        {{-- Statistiques de connexion --}}
                        <dl class="text-sm text-gray-600 dark:text-gray-400 space-y-3 flex-grow">
                            {{-- Connexions --}}
                            <div class="flex items-center justify-between py-1">
                                <dt class="flex items-center">
                                    <span class="font-medium">Connexions ({{ $periodOptions[$filters['period']] }}) :</span>
                                </dt>
                                <dd class="font-bold text-blue-600 dark:text-blue-400 text-lg">
                                    {{ number_format($user->connections_count) }}
                                </dd>
                            </div>

                            {{-- Dernière Activité --}}
                            <div class="flex items-center justify-between py-1">
                                <dt class="flex items-center">
                                    <span class="font-medium">Dernière activité :</span>
                                </dt>
                                <dd class="font-semibold text-gray-900 dark:text-white text-right">
                                    {{ $user->last_activity_date->diffForHumans() }}
                                    <span class="block text-xs text-gray-500 dark:text-gray-400">({{ $user->last_activity_date->format('d/m/Y H:i') }})</span>
                                </dd>
                            </div>

                            {{-- IPs Uniques --}}
                            <div class="flex items-center justify-between py-1">
                                <dt class="flex items-center space-x-1">
                                    <span class="font-medium">IPs uniques :</span>
                                </dt>
                                <dd class="font-semibold text-gray-900 dark:text-white flex items-center space-x-2">
                                    <span class="text-lg {{ $isSuspicious ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">{{ $user->unique_ips_count }}</span>
                                    @if($isSuspicious)
                                        <span class="text-red-500 bg-red-100 dark:bg-red-900 px-2 py-0.5 rounded-full text-xs font-bold whitespace-nowrap flex items-center">
                                            {{-- Icône d'alerte --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                 fill="currentColor" class="w-4 h-4 inline-block mr-1">
                                               <path fill-rule="evenodd"
                                                     d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.223 4.5-2.599 4.5H4.645c-2.376 0-3.754-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75.75 0 01.75.75v3.75a.75.75.75 0 01-1.5 0V9a.75.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"
                                                     clip-rule="evenodd"/>
                                            </svg>
                                            Alerte
                                        </span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                @endforeach
            @endif
        </div>
    </div>


    {{-- 2. Version TABLEAU (PC : lg et plus) --}}
    <div class="hidden lg:block">
        <div wire:loading.class.delay="opacity-50 pointer-events-none"
             class="flow-root">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    @if ($users->isEmpty())
                        {{-- Message d'absence de données (Adapté pour la version tableau) --}}
                        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-100 dark:border-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-12 h-12 text-blue-400/70 mx-auto mb-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.944-1.5a4.5 4.5 0 1 0-2.614-2.614zM21 12a9 9 0 1 0-2.639 6.36m-2.672-6.69c.677 0 1.25.573 1.25 1.25s-.573 1.25-1.25 1.25-1.25-.573-1.25-1.25.573-1.25 1.25-1.25z"/>
                            </svg>
                            <p class="text-xl font-bold text-gray-700 dark:text-gray-200">Aucun utilisateur trouvé</p>
                            <p class="text-gray-500 dark:text-gray-400 mt-1">Veuillez vérifier les filtres appliqués ou
                                votre terme de recherche.</p>
                        </div>
                    @else
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-6">
                                        Utilisateur
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                        Type
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900 dark:text-white">
                                        Connexions ({{ $periodOptions[$filters['period']] }})
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900 dark:text-white">
                                        IPs uniques
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900 dark:text-white">
                                        Dernière activité
                                    </th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                        <span class="sr-only">Détails</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-800 bg-white dark:bg-gray-900">
                                @foreach ($users as $user)
                                    @php
                                        $morphAlias = \Illuminate\Database\Eloquent\Relations\Relation::getMorphAlias(get_class($user));
                                        $isSuspicious = $user->unique_ips_count > 5;
                                    @endphp
                                    <tr wire:key="table-user-{{ $user->id }}-{{ $user->user_type }}"
                                        wire:click="selectUser('{{ $user->id }}', '{{ $morphAlias }}')"
                                        class="hover:bg-gray-50 dark:hover:bg-gray-800 transition duration-150 cursor-pointer">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 flex-shrink-0">
                                                    <div class="h-10 w-10 flex-shrink-0">
                                                        {{-- Avatar Cercle --}}
                                                        <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center
                                                            text-blue-600 dark:text-blue-400 font-bold text-lg border border-blue-300 dark:border-blue-700"
                                                        >
                                                            {{ strtoupper(substr($user->prenom ?? '', 0, 1) . substr($user->nom ?? '', 0, 1)) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="font-medium text-gray-900 dark:text-white">{{ $user->prenom }} {{ $user->nom }}</div>
                                                    <div class="text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5
                                                      {{ $isSuspicious ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' }}">
                                                    {{ ucfirst($morphAlias) }}
                                                </span>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-right font-bold text-blue-600 dark:text-blue-400">
                                            {{ number_format($user->connections_count) }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-right font-semibold">
                                            <div class="flex justify-end items-center space-x-2">
                                                    <span class="{{ $isSuspicious ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                                        {{ $user->unique_ips_count }}
                                                    </span>
                                                @if($isSuspicious)
                                                    <span class="text-red-500" title="Alerte : Plus de 5 IPs uniques">
                                                             <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                                  fill="currentColor" class="w-4 h-4">
                                                                <path fill-rule="evenodd"
                                                                      d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.223 4.5-2.599 4.5H4.645c-2.376 0-3.754-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75.75 0 01.75.75v3.75a.75.75.75 0 01-1.5 0V9a.75.75.75 0 01.75-.75zm0 8.25a.75.75.75 0 100-1.5.75.75 0 000 1.5z"
                                                                      clip-rule="evenodd"/>
                                                             </svg>
                                                        </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-right text-gray-900 dark:text-white">
                                            {{ $user->last_activity_date->diffForHumans() }}
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                ({{ $user->last_activity_date->format('d/m/Y H:i') }})
                                            </div>
                                        </td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            <a href="#"
                                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Détails<span
                                                        class="sr-only">, {{ $user->prenom }} {{ $user->nom }}</span></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Pagination (Améliorée) --}}
    @if ($users->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $users->links() }}
        </div>
    @endif
</div>
