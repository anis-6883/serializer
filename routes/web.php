<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\SerialController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\SettingsController;
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

Route::middleware(['install'])->group(function () {

    Auth::routes(['register' => false]);

    Route::get('/', [HomeController::class, 'index']);
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware(['auth'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::post('serials/reordering', [SerialController::class, 'reordering'])->name('serials.reordering');
        Route::resource('apps', AppController::class);
        Route::resource('serials', SerialController::class);
        Route::resource('episodes', EpisodeController::class);

        Route::middleware(['permission:admin'])->group(function () {
            // Settings Controller
            Route::any('/general_settings', [SettingsController::class, 'general'])->name('general_settings');
            Route::post('/store_settings', [SettingsController::class, 'store_settings'])->name('store_settings');
            Route::any('/database_backup', [BackupController::class, 'index'])->name('database_backup');
        });
    });

});

Route::get('/cache', function(){
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    // Artisan::call('key:generate');
    return redirect('/general_settings')->with('success', _lang('Cache Cleaned Sucessfully!'));
})->name('cache_clean');

Route::get('/installation', [InstallController::class, 'index']);
Route::get('/install/database', [InstallController::class, 'database']);
Route::post('/install/process_install', [InstallController::class, 'process_install']);
Route::get('/install/create_user', [InstallController::class, 'create_user']);
Route::post('/install/store_user', [InstallController::class, 'store_user']);
Route::get('/install/system_settings', [InstallController::class, 'system_settings']);
Route::post('/install/finish', [InstallController::class, 'final_touch']);