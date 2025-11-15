<?php

namespace Modules\AdminBase\Traits;

use App\Models\Ban;
use App\Models\UserNotificationPreference;

trait HasUserFeatures
{
    /**
     * Relation vers les préférences de notification
     */
    public function notificationPreferences()
    {
        return $this->morphOne(UserNotificationPreference::class, 'notifiable')->latest();
    }


}
