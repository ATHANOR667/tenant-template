<?php

namespace App\Providers\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'admin' => 'Modules\AdminBase\Models\Admin',
            'system' => 'Modules\AdminBase\Models\System',
            'super-admin' => 'Modules\AdminBase\Models\SuperAdmin',
            'permission' => 'Spatie\Permission\Models\Permission',
            'role' => 'Spatie\Permission\Models\Role',
            'user-connection-log' => 'Modules\AdminBase\Models\UserConnectionLog',
            'model-activity-log' => 'Modules\AdminBase\Models\ModelActivityLog',
        ]);

     /*   Gate::define('viewPulse', function ( ?Authenticatable $user) {
            return $user
                && $user->can('use-pulse');
        });*/
    }
}
