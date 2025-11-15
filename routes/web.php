<?php

use Illuminate\Support\Facades\Route;




Route::name('client.')
    ->group(function (){

        Route::get('/',function (){return view('welcome');})->name('welcome');

    });



