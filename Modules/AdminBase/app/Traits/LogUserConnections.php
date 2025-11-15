<?php

namespace Modules\AdminBase\Traits;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Modules\AdminBase\Models\UserConnectionLog;
use Modules\AdminBase\Services\LogUserConnectionService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

// Ajout pour générer un UUID

trait LogUserConnections
{
    /**
     * Boot the trait and register event listeners.
     */
    protected static function bootLogUserConnections(): void
    {
        Event::listen(Login::class, function ($event) {
            self::logUserLogin($event->user, $event->guard);
        });

        Event::listen(Logout::class, function ($event) {
            if ($event->user) {
                self::logUserLogout($event->user, $event->guard);
            }
        });
    }

    /**
     * Logs the start of a user session for a specific guard.
     *
     * @param Authenticatable $user The authenticated user instance.
     * @param string $guard The authentication guard name.
     */
    protected static function logUserLogin(Authenticatable $user, string $guard): void
    {
        // Générer un identifiant de session fixe (UUID)
        $fixedSessionId = (string) Str::uuid();

        // Stocker l'identifiant fixe dans la session
        session()->put('fixed_session_id', $fixedSessionId);

        $ip = Request::ip();
        $locationData = LogUserConnectionService::getLocationDataFromIp($ip);

        $location = null;
        if ($locationData) {
            $location = implode(', ', array_filter([
                $locationData->cityName ?? null,
                $locationData->regionName ?? null,
                $locationData->countryName ?? null,
            ]));
        }

        // Calculer la date d'expiration initiale basée sur session.lifetime
        $sessionLifetime = config("session.lifetime_{$guard}", config('session.lifetime', 120));
        $expiresAt = now()->addMinutes($sessionLifetime);

        UserConnectionLog::create([
            'user_id' => $user->getKey(),
            'user_type' => $user->getMorphClass(),
            'session_id' => $fixedSessionId, // Utiliser l'identifiant fixe
            'ip_address' => $ip,
            'device_info' => Request::userAgent(),
            'location' => $location,
            'session_start' => now(),
            'last_activity' => now(),
            'session_end' => null,
            'session_expires_at' => $expiresAt,
        ]);
    }

    /**
     * Updates the connection log with the session end for a specific guard.
     *
     * @param Authenticatable $user The authenticated user instance.
     * @param string $guard The authentication guard name.
     */
    protected static function logUserLogout(Authenticatable $user, string $guard): void
    {
        // Récupérer l'identifiant fixe depuis la session
        try {
            $fixedSessionId = session()->get('fixed_session_id');
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            Log::error($e->getMessage());
        }

        if (!$fixedSessionId) {
            \Log::warning("No fixed session ID found during logout for user ID: {$user->getKey()}");
            return;
        }

        $lastSession = UserConnectionLog::where('user_id', $user->getKey())
            ->where('user_type', $user->getMorphClass())
            ->where('session_id', $fixedSessionId)
            ->whereNull('session_end')
            ->latest('session_start')
            ->first();

        if ($lastSession) {
            $lastSession->update([
                'session_end' => now() ,
                'last_activity' => now() ,
            ]);
            // Supprimer l'identifiant fixe de la session après la déconnexion
            session()->forget('fixed_session_id');
        } else {
            \Log::warning("No active session found to logout for user ID: {$user->getKey()} with fixed session ID: {$fixedSessionId}");
        }
    }

    /**
     * Get the connection history for the user.
     *
     * @return MorphMany
     */
    public function connectionLogs(): MorphMany
    {
        return $this->morphMany(UserConnectionLog::class, 'user');
    }
}
