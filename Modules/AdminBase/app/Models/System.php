<?php

namespace Modules\AdminBase\Models;

use Modules\AdminBase\Traits\HasBanFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class System extends Model
{
    use HasBanFeature ;

    protected $table = 'systems';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['nom', 'prenom'];

    /**
     * Empêcher plusieurs entrées en base
     */
    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (self::query()->exists()) {
                throw new \Exception("Il ne peut y avoir qu'une seule entrée dans la table systems.");
            }

            if (!$model->id) {
                $model->id = Str::uuid()->toString();
            }

            $model->nom = 'System';
            $model->prenom = ' ';
        });
    }
}
