<?php

use Illuminate\Support\Facades\Route;
use Modules\AdminBase\Http\Controllers\Admin\AuthController;


/** ROUTES SUPER-ADMIN */

Route::name('super-admin.')
    ->prefix('super-admin')
    ->group(function (){

        /** AUTHENTICATION  */

        Route::name('auth.')
            ->prefix('auth')
            ->controller(\Modules\AdminBase\Http\Controllers\SuperAdmin\AuthController::class)
            ->group(function (){

                /**  WHILE DISCONNECTED */

                Route::name('disconnected.')
                    ->prefix('disconnected')
                    ->group(function (){
                        Route::get('login','loginView')->name('loginView');
                        Route::post('login','login')->name('login');
                    });


                /**  WHILE CONNECTED */

                Route::name('connected.')
                    ->prefix('connected')
                    ->middleware( [
                        Modules\AdminBase\Http\Middleware\SuperAdminMiddleware::class ,
                        Modules\AdminBase\Http\Middleware\UpdateSessionExpiration::class ,
                    ])
                    ->group(function (){
                        Route::delete('logout','logout')->name('logout');
                    });
            });






        /** SIMPLES ROUTES */

        Route::controller(\Modules\AdminBase\Http\Controllers\SuperAdmin\BaseController::class)
            ->middleware( [
                Modules\AdminBase\Http\Middleware\SuperAdminMiddleware::class ,
                Modules\AdminBase\Http\Middleware\UpdateSessionExpiration::class ,
            ])            ->group(function (){
                Route::get('/','profileView')->name('profileView');
                Route::get('/profile','profileView')->name('profileView');
                Route::get('/manage-admins','manageAdminsView')->name('manageAdminsView');
            });





    });




/** ROUTES ADMIN */

Route::name('admin.')
    ->prefix('admin')
    ->group(function (){

        /** AUTHENTICATION  */

        Route::name('auth.')
            ->prefix('auth')
            ->controller(AuthController::class)
            ->group(function (){

                /**  WHILE DISCONNECTED */

                Route::name('disconnected.')
                    ->prefix('disconnected')
                    ->group(function (){
                        Route::get('login','loginView')->name('loginView');
                        Route::post('login','login')->name('login');

                        Route::get('signup','signupView')->name('signupView');
                        Route::post('signup','signup')->name('signup');
                    });


                /**  WHILE CONNECTED */

                Route::name('connected.')
                    ->prefix('connected')
                    ->middleware( [
                        Modules\AdminBase\Http\Middleware\AdminMiddleware::class ,
                        Modules\AdminBase\Http\Middleware\UpdateSessionExpiration::class ,
                    ])                    ->group(function (){
                        Route::delete('logout','logout')->name('logout');

                });



            });

    });

Route::controller( Modules\AdminBase\Http\Controllers\Admin\BaseController::class)
    ->prefix('admin/')
    ->name('admin.')
    ->middleware( [
        Modules\AdminBase\Http\Middleware\AdminMiddleware::class ,
        Modules\AdminBase\Http\Middleware\UpdateSessionExpiration::class ,
    ])->group(function (){
        Route::get('/','profileView')->name('profileView');
        Route::get('/profile','profileView')->name('profileView');
        Route::get('/logsDashboard','logsDashboardView')->name('logsDashboardView');
        Route::get('/finances','finances')->name('finances');

    });



