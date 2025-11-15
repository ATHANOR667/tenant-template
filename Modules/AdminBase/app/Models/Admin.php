<?php

namespace Modules\AdminBase\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Modules\AdminBase\Database\Factories\AdminFactory;
use Modules\AdminBase\Traits\HasActivityLog;
use Modules\AdminBase\Traits\HasBanFeature;
use Modules\AdminBase\Traits\HasUserFeatures;
use Modules\AdminBase\Traits\LogUserConnections;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use SoftDeletes, HasFactory, Notifiable, HasRoles , HasPermissions ,
        // traits personnels
        HasActivityLog, HasUserFeatures, HasBanFeature, LogUserConnections;

    protected $keyType = 'string';
    public $incrementing = false;



    protected $fillable = [
        'matricule', 'nom', 'prenom', 'email', 'password', 'telephone',
        'passcode', 'dateNaissance', 'lieuNaissance', 'pieceIdentiteRecto',
        'pieceIdentiteVerso', 'photoProfil' , 'pays' , 'ville',
        'password_changed_at','passcode_reset_status', 'passcode_reset_date'
    ];

    protected $casts = [
        'password_changed_at' => 'datetime',
        'passcode_reset_date' => 'datetime',
        'dateNaissance' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];




    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
            $model->matricule = (string) Str::uuid();
        });
    }

    protected static function newFactory(): AdminFactory
    {
        return AdminFactory::new();
    }
}
