<x-pulse>

    <livewire:pulse.servers cols="full" />

    <livewire:pulse.usage cols="4" rows="2" />

    <livewire:pulse.queues cols="4" />

    <livewire:pulse.cache cols="4" />


    <livewire:pulse.slow-queries cols="8" />

    <livewire:pulse.exceptions cols="6" />

    <livewire:pulse.slow-requests cols="6" />

    <livewire:pulse.slow-jobs cols="6" />

    <livewire:pulse.slow-outgoing-requests cols="6" />

    {{-- Recoder pour les logs--}}
    <livewire:pulse.log-files cols="full" />

    {{--  Package perimés  --}}
    <livewire:outdated cols='4' rows='2' />

    {{--  Taches programmées --}}
    <livewire:pulse.schedule cols="8" />

    {{--  Erreurs de validation courantes --}}
    <livewire:pulse.validation-errors cols="full" />


    {{-- recorder pour la base de donnée --}}
    @php
        $db = config('database.default');
    @endphp

    {{-- ==================== MYSQL ==================== --}}
    @if ($db === 'mysql')
        {{-- 1️⃣ Threads actifs --}}
        <livewire:database cols='6' title="Active Threads"
                           :values="['Threads_connected', 'Threads_running']"
                           :graphs="[
            'avg' => ['Threads_connected' => '#ffffff', 'Threads_running' => '#3c5dff'],
        ]"
        />

        {{-- 2️⃣ Connexions --}}
        <livewire:database cols='6' title="Connections"
                           :values="['Connections', 'Max_used_connections']"
        />

        {{-- 3️⃣ InnoDB --}}
        <livewire:database cols='full' title="InnoDB Buffer Activity"
                           :values="['Innodb_buffer_pool_reads', 'Innodb_buffer_pool_read_requests', 'Innodb_buffer_pool_pages_total']"
                           :graphs="[
            'avg' => ['Innodb_buffer_pool_reads' => '#ffffff', 'Innodb_buffer_pool_read_requests' => '#3c5dff'],
        ]"
        />
    @endif


    {{-- ==================== POSTGRESQL ==================== --}}
    @if ($db === 'pgsql')
        {{-- 1️⃣ Connexions actives --}}
        <livewire:database cols='6' title="Connexions actives"
                           :values="['numbackends']"
                           :graphs="[
            'avg' => ['numbackends' => '#3c5dff'],
        ]"
        />

        {{-- 2️⃣ Transactions --}}
        <livewire:database cols='6' title="Transactions"
                           :values="['xact_commit', 'xact_rollback']"
                           :graphs="[
            'sum' => ['xact_commit' => '#3c5dff', 'xact_rollback' => '#ff6363'],
        ]"
        />

        {{-- 3️⃣ Activité lecture / écriture --}}
        <livewire:database cols='full' title="Activité lecture / écriture"
                           :values="['blks_read', 'blks_hit', 'blk_read_time', 'blk_write_time']"
                           :graphs="[
            'avg' => [
                'blks_read' => '#ffffff',
                'blks_hit' => '#3c5dff',
                'blk_read_time' => '#ffb347',
                'blk_write_time' => '#ff6363',
            ],
        ]"
        />
    @endif



</x-pulse>
