<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\RedirectIfNotAuthenticated;
use App\Http\Middleware\LogRequests;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BackupController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route untuk login
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Grup route yang dilindungi oleh middleware RedirectIfNotAuthenticated
Route::middleware([RedirectIfNotAuthenticated::class])->group(function () {
    // Route untuk dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //Route untuk kegiatan
    //Route::prefix('kegiatan')->group(function () {
    Route::prefix('kegiatan')->group(function () {
        Route::get('/', [KegiatanController::class, 'index'])->name('kegiatan.index');
        Route::post('/store', [KegiatanController::class, 'store'])->name('kegiatan.store');
        Route::put('/{id}', [KegiatanController::class, 'update'])->name('kegiatan.update');
        Route::delete('/{id}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');
        Route::get('/print/{id}', [KegiatanController::class, 'print'])->name('kegiatan.print');
    });
});
// Route::middleware(['log.requests'])->group(function () {
//     Route::prefix('kegiatan')->group(function () {
//         Route::get('/', [KegiatanController::class, 'index'])->name('kegiatan.index');
//         Route::post('/store', [KegiatanController::class, 'store'])->name('kegiatan.store');
//         Route::put('/{id}', [KegiatanController::class, 'update'])->name('kegiatan.update');
//         Route::delete('/{id}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');
//         Route::get('/print/{id}', [KegiatanController::class, 'print'])->name('kegiatan.print');
//     });
// });
// Route untuk profil
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

// Route untuk user
Route::middleware(['auth'])->group(function () {
    Route::resource('user', UserController::class)->except(['show']);
    Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
});
// Route untuk Settings
Route::middleware(['auth'])->group(function () {
    Route::get('/settings', [BackupController::class, 'index'])->name('settings.index');
    Route::post('/settings/backup-database', [BackupController::class, 'backupDatabase'])->name('settings.backupDatabase');
    Route::post('/settings/restore-database', [BackupController::class, 'restoreDatabase'])->name('settings.restoreDatabase');
    Route::post('/settings/backup-data', [BackupController::class, 'backupData'])->name('settings.backupData');
    Route::post('/settings/restore-data', [BackupController::class, 'restoreData'])->name('settings.restoreData');
    Route::get('/settings/download-backup/{file}', [BackupController::class, 'downloadBackup'])->name('settings.downloadBackup');
    Route::delete('/settings/delete-backup/{file}', [BackupController::class, 'deleteBackup'])->name('settings.deleteBackup');
});