<?php

use App\Http\Middleware\LogRequests;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RegulationController;
use App\Http\Middleware\RedirectIfNotAuthenticated;

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

    // Route untuk kegiatan
    Route::prefix('kegiatan')->group(function () {
        Route::get('/', [KegiatanController::class, 'index'])->name('kegiatan.index');
        Route::post('/store', [KegiatanController::class, 'store'])->name('kegiatan.store');
        Route::put('/{id}', [KegiatanController::class, 'update'])->name('kegiatan.update');
        Route::delete('/{id}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');
        Route::get('/print/{id}', [KegiatanController::class, 'print'])->name('kegiatan.print');
    });

    // Route untuk peraturan
    Route::prefix('peraturan')->group(function () {
        Route::get('/', [RegulationController::class, 'index'])->name('regulations.index');
        Route::post('/store', [RegulationController::class, 'store'])->name('regulations.store');
        Route::get('/create', [RegulationController::class, 'create'])->name('regulations.create');
        Route::get('/{regulation}/edit', [RegulationController::class, 'edit'])->name('regulations.edit');
        Route::put('/{regulation}', [RegulationController::class, 'update'])->name('regulations.update');
        Route::delete('/{regulation}', [RegulationController::class, 'destroy'])->name('regulations.destroy');
        Route::get('/{regulation}/pdf', [RegulationController::class, 'showPdf'])->name('regulations.pdf');
        Route::get('/{regulation}', [RegulationController::class, 'show'])->name('regulations.show');
        
        // Rute untuk menimbang
        Route::post('/{regulation}/menimbang', [RegulationController::class, 'addMenimbang'])->name('regulations.addMenimbang');
        Route::put('/menimbang/{menimbang}', [RegulationController::class, 'editMenimbang'])->name('regulations.editMenimbang');
        Route::delete('/menimbang/{id}', [RegulationController::class, 'deleteMenimbang'])->name('regulations.deleteMenimbang');
        
        // Rute untuk mengingat
        Route::post('/{regulation}/mengingat', [RegulationController::class, 'addMengingat'])->name('regulations.addMengingat');
        Route::put('/mengingat/{mengingat}', [RegulationController::class, 'editMengingat'])->name('regulations.editMengingat');
        Route::delete('/mengingat/{id}', [RegulationController::class, 'deleteMengingat'])->name('regulations.deleteMengingat');
        
        // Rute untuk memutuskan
        Route::post('/{regulation}/memutuskan', [RegulationController::class, 'addMemutuskan'])->name('regulations.addMemutuskan');
        Route::put('/memutuskan/{memutuskan}', [RegulationController::class, 'editMemutuskan'])->name('regulations.editMemutuskan');
        Route::delete('/memutuskan/{id}', [RegulationController::class, 'deleteMemutuskan'])->name('regulations.deleteMemutuskan');
        
        // Rute untuk sub memutuskan
        Route::post('/memutuskan/{memutuskan}/sub', [RegulationController::class, 'addSubMemutuskan'])->name('regulations.addSubMemutuskan');
        Route::put('/subMemutuskan/{sub}', [RegulationController::class, 'editSubMemutuskan'])->name('regulations.editSubMemutuskan');
        Route::delete('/subMemutuskan/{id}', [RegulationController::class, 'deleteSubMemutuskan'])->name('regulations.deleteSubMemutuskan');
    });
});

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
