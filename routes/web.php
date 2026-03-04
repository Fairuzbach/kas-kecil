<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\PettyCash\CreateRequest; // <--- Jangan lupa import ini
use App\Livewire\PettyCash\Show;
use App\Livewire\Finance\Dashboard;
use App\Livewire\PettyCash\ReconciliationDashboard;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:finance'])->group(function () {
    Route::get('/finance/dashboard', Dashboard::class)->name('finance.dashboard');
    Route::get('/petty-cash/reconciliation', ReconciliationDashboard::class)->name('petty-cash.reconciliation');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/petty-cash/create', CreateRequest::class)->name('petty-cash.create');
    Route::get('/petty-cash/{pettyCashRequest}', Show::class)
        ->name('petty-cash.show');
});

require __DIR__ . '/auth.php';
