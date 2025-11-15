<?php

namespace Modules\AdminBase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class UserConnectionLog extends Model
{

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'user_type',
        'ip_address',
        'device_info',
        'location',
        'session_start',
        'session_end',
        'last_activity',
        'session_expires_at',
        'session_id',
    ];

    protected $casts = [
        'last_activity' => 'datetime',
        'session_start' => 'datetime',
        'session_end' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Generate a UUID for the primary key before creation.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    /**
     * Polymorphic relation to the connected user.
     */
    public function user(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope pour filtrer par période.
     */
    public function scopeForPeriod($query, $period): void
    {
        $startDate = match($period) {
            'today' => now()->startOfDay(),
            'last_7_days' => now()->subDays(7)->startOfDay(),
            'last_30_days' => now()->subDays(30)->startOfDay(),
            'last_90_days' => now()->subDays(90)->startOfDay(),
            default => null,
        };

        if ($startDate) {
            $query->where('session_start', '>=', $startDate);
        }
    }

    /**
     * Scope pour sessions actives (session_end null et last_activity récente).
     */
    public function scopeActiveSessionsRecentlyUsed($query , $minutes = 5 ): void
    {
        $query->whereNull('session_end')
            ->where('last_activity', '>=', now()->subMinutes($minutes));
    }

    /**
     * Scope pour filtrer par type d'utilisateur.
     */
    public function scopeByUserType($query, $userType): void
    {
        if ($userType) {
            $query->where('user_type', $userType);
        }
    }

    /**
     * Scope pour recherche par location ou IP.
     */
    public function scopeByLocationOrIp($query, $search): void
    {
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('location', 'like', "%$search%")
                    ->orWhere('ip_address', 'like', "%$search%");
            });
        }
    }
}
