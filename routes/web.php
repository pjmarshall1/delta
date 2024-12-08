<?php

use App\Http\Controllers\AggregateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Profile\ProfilePhotoController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\ScanImportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Redirect::to(route('dashboard.index'));
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('/scans', [ScanController::class, 'index'])->name('scans.index');
    Route::get('/scans/{scan}', [ScanController::class, 'show'])->name('scans.show');
    Route::post('/scans/import', ScanImportController::class)->name('scans.import');

    Route::get('/aggregate', AggregateController::class)->name('aggregate');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/profile/photo', [ProfilePhotoController::class, 'store'])->name('profile.photo.store');
    Route::delete('/profile/photo', [ProfilePhotoController::class, 'destroy'])->name('profile.photo.destroy');
});

require __DIR__.'/auth.php';
