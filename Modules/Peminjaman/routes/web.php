<?php

use Illuminate\Support\Facades\Route;
use Modules\Peminjaman\Http\Controllers\PeminjamanController;

// Dapat diakses admin maupun user (cukup login)
Route::middleware('auth')->prefix('peminjaman')->name('peminjaman.')->group(function () {
    Route::get('/', [PeminjamanController::class, 'index'])->name('index');
    Route::get('/data', [PeminjamanController::class, 'data'])->name('data');       // JSON list
    Route::post('/', [PeminjamanController::class, 'store'])->name('store');         // JSON
    Route::get('/{peminjaman}', [PeminjamanController::class, 'show'])->name('show'); // JSON
    Route::put('/{peminjaman}', [PeminjamanController::class, 'update'])->name('update');
    Route::delete('/{peminjaman}', [PeminjamanController::class, 'destroy'])->name('destroy');
});
