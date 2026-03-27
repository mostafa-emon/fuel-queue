<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\PublicController;

Route::get('/', [PublicController::class, 'index'])->name('home');

Route::get('/status', [PublicController::class, 'index'])->name('status');

Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'admin') return redirect()->route('admin.dashboard');
    if ($role === 'operator') return redirect()->route('operator.dashboard');
    return redirect()->route('user.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // Admin routes
    Route::middleware('can:isAdmin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::post('/admin/daily-acceptance', [AdminController::class, 'store'])->name('admin.acceptance.store');
    });

    // Operator routes
    Route::middleware('can:isOperator')->group(function () {
        Route::get('/operator/dashboard', [OperatorController::class, 'index'])->name('operator.dashboard');
        Route::post('/operator/verify', [OperatorController::class, 'verify'])->name('operator.verify');
    });

    // User routes
    Route::middleware('can:isUser')->group(function () {
        Route::get('/user/dashboard', [QueueController::class, 'index'])->name('user.dashboard');
        Route::post('/user/book', [QueueController::class, 'store'])->name('user.book');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
