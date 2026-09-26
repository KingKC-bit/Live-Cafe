<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| This file is the spine. Feature routes are organised into separate files
| and loaded here. Do not put feature logic directly into this file.
|--------------------------------------------------------------------------
*/

// Public home
Route::get('/', function () {
    return view('home');
})->name('home');

// Feature route files
require __DIR__.'/shop.php';
require __DIR__.'/running.php';
require __DIR__.'/admin.php';
require __DIR__.'/pos.php';