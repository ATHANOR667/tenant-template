<?php

namespace Database\Seeders;

use App\Models\UserNotificationPreference;
use Illuminate\Database\Seeder;
use Modules\AdminBase\Models\Admin;
use Modules\AdminBase\Models\SuperAdmin;

class UserNotificationPreferenceSeeder extends Seeder
{
    public function run(): void
    {
        // Préférences pour SuperAdmins
        $superAdmins = SuperAdmin::all();
        for ($i = 0; $i < $superAdmins->count(); $i++) {
            UserNotificationPreference::factory()->create([
                'notifiable_id' => $superAdmins[$i]->id,
                'notifiable_type' => 'super-admin',
            ]);
        }

        // Préférences pour Admins
        $admins = Admin::all();
        for ($i = 0; $i < $admins->count(); $i++) {
            UserNotificationPreference::factory()->create([
                'notifiable_id' => $admins[$i]->id,
                'notifiable_type' => 'admin',
            ]);
        }



    }
}

