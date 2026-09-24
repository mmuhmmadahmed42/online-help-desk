<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectManagerController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
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

// Admin routes — only accessible by role "admin"
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/users/create', [AdminController::class, 'create'])->name('create');
    Route::post('/users', [AdminController::class, 'store'])->name('store');
    Route::post('/users/{user}/activate', [AdminController::class, 'activate'])->name('activate');
    Route::post('/users/{user}/deactivate', [AdminController::class, 'deactivate'])->name('deactivate');
    Route::get('/users/{user}/change-password', [AdminController::class, 'editPassword'])->name('users.change-password');
    Route::post('/users/{user}/change-password', [AdminController::class, 'updatePassword'])->name('users.update-password');
    Route::get('/password-requests', [AdminController::class, 'passwordRequests'])->name('password-requests');
    Route::get('/password-requests/new', [AdminController::class, 'newPasswordRequests'])->name('password-requests.new');
});

require __DIR__.'/auth.php';