<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\PettyCash\CreateRequest; // <--- Jangan lupa import ini
use App\Livewire\PettyCash\Show;

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

    // --- ROUTE KITA ---
    Route::get('/petty-cash/create', CreateRequest::class)->name('petty-cash.create');
    // Route untuk melihat Detail Pengajuan
    // {pettyCashRequest} adalah parameter ID yang otomatis ditangkap oleh Livewire
    Route::get('/petty-cash/{pettyCashRequest}', \App\Livewire\PettyCash\Show::class)
        ->name('petty-cash.show');
});

// Baris ini sekarang PASTI berhasil karena filenya sudah digenerate ulang
require __DIR__ . '/auth.php';
