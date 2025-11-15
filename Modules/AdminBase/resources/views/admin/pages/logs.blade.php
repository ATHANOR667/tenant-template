@extends('adminbase::admin.connected-base')

@section('title', 'Logs et monitoring')

@section('content')

    <div x-data="{ currentTab: 'connections' }" class="p-4 md:p-8">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-6">Tableau de bord des Logs</h1>

        <div class="mb-8 border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button @click="currentTab = 'connections'"
                        :class="currentTab === 'connections' ? 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 hover:border-gray-300 dark:hover:text-gray-200 dark:hover:border-gray-600'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-300">
                    Logs de Connexion
                </button>
                <button @click="currentTab = 'activities'"
                        :class="currentTab === 'activities' ? 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 hover:border-gray-300 dark:hover:text-gray-200 dark:hover:border-gray-600'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-300">
                    Logs d'Activité
                </button>
            </nav>
        </div>

        <div x-show="currentTab === 'connections'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
            @livewire('adminbase::logs.connexions.user-connection-log-component')
        </div>

        <div x-show="currentTab === 'activities'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
            @livewire('adminbase::logs.history.model-activity-log-component')
        </div>

    </div>

@endsection
