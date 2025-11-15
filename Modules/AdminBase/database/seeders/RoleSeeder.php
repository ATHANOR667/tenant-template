<?php

namespace Modules\AdminBase\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [

            /** PERMISSIONS ADMIN LIEES AU MONITORING  */
            [
                'name' => 'use-horizon',
                'categorie' => 'monitoring',
                'guard_name' => 'admin',
            ],
            [
                'name' => 'use-pulse',
                'categorie' => 'monitoring',
                'guard_name' => 'admin',
            ],
            [
                'name' => 'customs-logs',
                'categorie' => 'monitoring',
                'guard_name' => 'admin',
            ],




        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                [
                    'name' => $perm['name'],
                    'guard_name' => $perm['guard_name'],
                    'categorie' => $perm['categorie'] ,
                ]
            );
        }
    }
}
