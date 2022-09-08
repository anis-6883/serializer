<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use App\Http\Controllers\SerialController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Install\InstallController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::middleware(['install'])->group(function () {

    Auth::routes(['register' => false]);

    Route::get('/', [HomeController::class, 'index']);
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware(['auth'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::resource('apps', AppController::class);
        Route::resource('serials', SerialController::class);

    });

// });

Route::get('/installation', [InstallController::class, 'index']);
Route::get('/install/database', [InstallController::class, 'database']);
Route::post('/install/process_install', [InstallController::class, 'process_install']);
Route::get('/install/create_user', [InstallController::class, 'create_user']);
Route::post('/install/store_user', [InstallController::class, 'store_user']);
Route::get('/install/system_settings', [InstallController::class, 'system_settings']);
Route::post('/install/finish', [InstallController::class, 'final_touch']);