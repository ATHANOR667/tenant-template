<?php

namespace Modules\AdminBase\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\AdminBase\Models\Ban;

trait HasBanFeature
{
    /**
     * Les bans subis par ce modèle (User/Admin/Autre)
     */
    public function bans(): MorphMany
    {
        return $this->morphMany(Ban::class, 'bannable');
    }

    /**
     * Les bans effectués par ce modèle
     */
    public function bansGiven(): MorphMany
    {
        return $this->morphMany(Ban::class, 'bannedBy');
    }

    /**
     * Les bans levés par ce modèle
     */
    public function bansLifted(): MorphMany
    {
        return $this->morphMany(Ban::class, 'unbannedBy');
    }

    /**
     * Vérifie si le modèle est actuellement banni
     */
    public function isBanned(): bool
    {
        return $this->bans()
            ->whereNull('deleted_at')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }
}
