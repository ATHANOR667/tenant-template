<?php

namespace Modules\AdminBase\Models;

use Modules\AdminBase\Database\Factories\SuperAdminFactory;
use Modules\AdminBase\Traits\HasActivityLog;
use Modules\AdminBase\Traits\HasBanFeature;
use Modules\AdminBase\Traits\HasUserFeatures;
use Modules\AdminBase\Traits\LogUserConnections;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;


class SuperAdmin extends Authenticatable
{

    use SoftDeletes , HasFactory , Notifiable , HasRoles
        , HasActivityLog , HasBanFeature , HasUserFeatures , LogUserConnections;

    protected $keyType = 'string';
    public $incrementing = false;
    public string $guard = 'super-admin';

    protected $fillable = ['password', 'email'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    protected static function newFactory(): SuperAdminFactory
    {
        return SuperAdminFactory::new();
    }


}
