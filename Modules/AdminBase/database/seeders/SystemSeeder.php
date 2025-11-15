<?php

namespace Modules\AdminBase\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\AdminBase\Models\System;

class SystemSeeder extends Seeder
{
    public function run(): void
    {
        if (!System::query()->exists()) {
            System::create([]);
        }
    }
}
