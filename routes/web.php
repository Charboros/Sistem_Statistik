<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ViewerController;

Route::get('/', [ViewerController::class, 'index'])->name('home');

use App\Http\Controllers\Admin\AdminController;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/save', [AdminController::class, 'store'])->name('store');
    Route::post('/dashboard/add-layanan', [AdminController::class, 'addLayanan'])->name('add-layanan');
    Route::delete('/dashboard/layanan', [AdminController::class, 'deleteLayanan'])->name('delete-layanan');
    
    Route::post('/dashboard/lokasi', [AdminController::class, 'addLokasi'])->name('add-lokasi');
    Route::put('/dashboard/lokasi', [AdminController::class, 'updateLokasi'])->name('update-lokasi');
    Route::delete('/dashboard/lokasi', [AdminController::class, 'deleteLokasi'])->name('delete-lokasi');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', function () {
    return 'Laravel Version: ' . app()->version() . ' | PHP Version: ' . phpversion();
});


require __DIR__.'/auth.php';
