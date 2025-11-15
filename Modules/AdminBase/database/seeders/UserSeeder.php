<?php

namespace Modules\AdminBase\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\AdminBase\Models\Admin;
use Modules\AdminBase\Models\SuperAdmin;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Créer 1 SuperAdmins
        for ($i = 0; $i < 1 ; $i++) {
            SuperAdmin::factory()->create();
        }

        // Créer 5 Admins
        for ($i = 0; $i < 5; $i++) {
            Admin::factory()->create();
        }

    }
}

