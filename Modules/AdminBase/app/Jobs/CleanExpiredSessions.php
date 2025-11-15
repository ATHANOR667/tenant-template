<?php

namespace Modules\AdminBase\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Modules\AdminBase\Models\UserConnectionLog;

class CleanExpiredSessions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        UserConnectionLog::whereNull('session_end') // toutes les sessions non marquees comme expirées
            ->where('session_expires_at', '<=', now()) // dont la date d'expiration prévue est arrivée
            ->update(['session_end' => DB::raw('COALESCE(session_expires_at, NOW())')]); // marques les comme expirées
    }
}
