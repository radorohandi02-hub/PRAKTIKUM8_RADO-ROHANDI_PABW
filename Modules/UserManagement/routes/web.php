<?php

use Illuminate\Support\Facades\Route;
use Modules\UserManagement\Http\Controllers\UserController;

// Khusus admin: user biasa akan mendapat error 403
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::resource('users', UserController::class)->except('show')->names('users');
});
