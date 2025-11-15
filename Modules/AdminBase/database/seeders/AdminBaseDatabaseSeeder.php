<?php

namespace Modules\AdminBase\Database\Seeders;

use Illuminate\Database\Seeder;

class AdminBaseDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            SystemSeeder::class,
            UserSeeder::class

        ]);
    }
}
