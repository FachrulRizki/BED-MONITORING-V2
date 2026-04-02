<?php

use App\Http\Controllers\AmprahanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthDashboardController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MonitorController::class, 'index'])->name('monitor.index');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthDashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::resource('rooms', RoomController::class)->middleware('admin');

    Route::get('/amprahans', [AmprahanController::class, 'index'])->name('amprahans.index');
    Route::get('/amprahans/create', [AmprahanController::class, 'create'])->name('amprahans.create');
    Route::post('/amprahans', [AmprahanController::class, 'store'])->name('amprahans.store');
    Route::get('/amprahans/{amprahan}', [AmprahanController::class, 'show'])->name('amprahans.show');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
});
