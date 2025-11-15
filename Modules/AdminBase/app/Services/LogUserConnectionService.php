<?php

namespace Modules\AdminBase\Services;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Modules\AdminBase\Models\UserConnectionLog;
use Stevebauman\Location\Facades\Location;

class LogUserConnectionService
{
    /**
     * Fetches location data from an IP address, with caching and IP validation.
     *
     * @param string $ip The IP address to fetch location for.
     * @return object|null Location data object or null if invalid or failed.
     */
    public static function getLocationDataFromIp(string $ip): ?object
    {

        // Validate IP address
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            \Log::warning("Invalid IP address provided: {$ip}");
            return null;
        }

        // Generate cache key based on IP
        $cacheKey = 'location_data_' . md5($ip);

        // Use Cache::remember to store location data for 24 hours
        return Cache::remember($cacheKey, now()->addHours(24), function () use ($ip) {
            try {
                $locationData = Location::get($ip);
                // Return null if the API call fails or IP is local
                return $locationData === false ? null : $locationData;
            } catch (\Exception $e) {
                // Log the error for debugging
                \Log::warning("Failed to retrieve location for IP {$ip}: {$e->getMessage()}");
                return null;
            }
        });
    }

    /**
     * Updates the session expiration time for a recent active session.
     *
     * @param Authenticatable $user The authenticated user instance.
     * @param string $guard The authentication guard name.
     */
    public static function updateSessionExpiration(Authenticatable $user, string $guard): void
    {
        // Récupérer la durée de vie de la session
        $sessionLifetime = config("session.lifetime_{$guard}", config('session.lifetime', 120));
        $expiresAt = now()->addMinutes($sessionLifetime);

        // Rechercher une session active récente pour cet utilisateur
        $lastSession = UserConnectionLog::where('user_id', $user->getKey())
            ->where('user_type', $user->getMorphClass())
            ->whereNull('session_end')
            ->where('session_expires_at', '>', now()) // Session non expirée
            ->latest('session_start')
            ->first();

        if ($lastSession) {
            // Mettre à jour la date d'expiration
            $lastSession->update([
                'session_expires_at' => $expiresAt ,
                'last_activity' => now()
                ]);
        } else {
            \Log::warning("No recent active session found to update expiration for user ID: {$user->getKey()}");
        }
    }
}
