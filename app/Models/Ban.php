<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Ban extends Model
{
    use SoftDeletes , HasFactory;

    protected $table = 'bans';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'bannable_id',
        'bannable_type',

        'banned_by_id',
        'banned_by_type',

        'unbanned_by_id',
        'unbanned_by_type',

        'motif',
        'expires_at',
        'ban_level_id',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($ban) {
            if (!$ban->id) {
                $ban->id = Str::uuid()->toString();
            }
        });
    }

    /**
     * Entité bannie (User, Admin, autre…)
     */
    public function bannable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Auteur du ban (User, Admin ou System)
     */
    public function bannedBy(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Auteur du unban (User, Admin ou System)
     */
    public function unbannedBy(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Créer un ban sans gérer manuellement les morphs
     */
    public static function createFor($bannable, array $attributes, $bannedBy): self
    {
        $ban = new static($attributes);

        // Associer la cible
        $ban->bannable()->associate($bannable);

        // Associer l'auteur
        $ban->bannedBy()->associate($bannedBy);

        $ban->save();

        return $ban;
    }

    /**
     * Lever le ban avec l'auteur (User/Admin/System)
     */
    public function liftBy($unbannedBy): self
    {
        $this->unbannedBy()->associate($unbannedBy);
        $this->save();

        $this->delete();

        return $this;
    }
}
