<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityController; 
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('categories', CategoryController::class);
});


Route::middleware(['auth', 'verified'])->group(function () {
   
    Route::resource('tickets', TicketController::class)->except(['edit', 'update']);
    
    Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign'])
        ->name('tickets.assign');

    Route::post('/tickets/{ticket}/comment', [TicketController::class, 'addComment'])
        ->name('tickets.comment');
        
    Route::put('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])
        ->name('tickets.update-status');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/user/dashboard', function () {
        return app(DashboardController::class)->index();
    })->name('user.dashboard');

    Route::get('/activity', [ActivityController::class, 'index'])->name('activity.index');
    
    Route::get('/activity/export', [ActivityController::class, 'export'])->name('activity.export');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';