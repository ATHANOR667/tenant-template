<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserNotificationPreference extends Model
{
    use HasFactory ;

    protected $keyType = 'string';
    public $incrementing = false;

    public const CHANNELS = ['mail', 'sms', 'whatsapp'];

    protected $fillable = [
        'notifiable_id',
        'notifiable_type',
        'preferences',
    ];

    protected $casts = [
        'preferences' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }



    /**
     * Relation polymorphique vers l'utilisateur
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

}
