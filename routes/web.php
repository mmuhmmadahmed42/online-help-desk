<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectManagerController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// User ticket routes — only accessible by role "user"
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::resource('tickets', TicketController::class);
    Route::delete('/attachments/{attachment}', [TicketController::class, 'destroyAttachment'])->name('attachments.destroy');
});

// Project Manager routes — only accessible by role "project_manager"
Route::middleware(['auth', 'role:project_manager'])->prefix('pm')->name('pm.')->group(function () {
    Route::get('/', [ProjectManagerController::class, 'index'])->name('index');
    Route::post('/tickets/{ticket}/assign', [ProjectManagerController::class, 'assign'])->name('assign');
    Route::get('/new-tickets', [ProjectManagerController::class, 'newTickets'])->name('new-tickets');
    Route::get('/notifications', [ProjectManagerController::class, 'notifications'])->name('notifications');
});

// Backend/Frontend Team routes — accessible by both team roles
Route::middleware(['auth', 'role:backend_team,frontend_team'])->prefix('team')->name('team.')->group(function () {
    Route::get('/', [TeamController::class, 'index'])->name('index');
    Route::get('/my-history', [TeamController::class, 'myHistory'])->name('my-history');
    Route::get('/tickets/{ticket}', [TeamController::class, 'show'])->name('show');
    Route::get('/tickets/{ticket}/history', [TeamController::class, 'history'])->name('history');
    Route::post('/tickets/{ticket}/comment', [TeamController::class, 'comment'])->name('comment');
    Route::post('/tickets/{ticket}/complete', [TeamController::class, 'complete'])->name('complete');
});

require __DIR__.'/auth.php';