<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\PettyCash\CreateRequest;
use App\Enums\PettyCashStatus;
use App\Models\PettyCashRequest;
use Carbon\Carbon;
use App\Livewire\PettyCash\Show;
use App\Livewire\Finance\Dashboard;
use App\Livewire\PettyCash\ReconciliationDashboard;

Route::get('/', function () {
    $currentMonth = Carbon::now()->month;
    $currentYear = Carbon::now()->year;

    // 1. Hitung total dokumen yang masuk bulan ini
    $requestsThisMonth = PettyCashRequest::whereMonth('created_at', $currentMonth)
        ->whereYear('created_at', $currentYear)
        ->count();

    // 2. Hitung Approval Rate (Persentase dokumen yang disetujui)
    $totalProcessed = PettyCashRequest::whereIn('status', [PettyCashStatus::PAID, PettyCashStatus::REJECTED])->count();
    $approvedCount = PettyCashRequest::where('status', PettyCashStatus::PAID)->count();
    $approvalRate = $totalProcessed > 0 ? round(($approvedCount / $totalProcessed) * 100, 1) : 0;

    // 3. Antrean saat ini (Masih pending/belum selesai)
    $pendingCount = PettyCashRequest::whereNotIn('status', [
        PettyCashStatus::PAID,
        PettyCashStatus::REJECTED,
        PettyCashStatus::REVISION
    ])->count();

    // 4. Disetujui khusus hari ini
    $approvedToday = PettyCashRequest::where('status', PettyCashStatus::PAID)
        ->whereDate('updated_at', Carbon::today())
        ->count();

    // 5. Total dokumen yang ditolak / butuh revisi
    $rejectedCount = PettyCashRequest::whereIn('status', [
        PettyCashStatus::REJECTED,
        PettyCashStatus::REVISION
    ])->count();

    return view('welcome', compact(
        'requestsThisMonth',
        'approvalRate',
        'pendingCount',
        'approvedToday',
        'rejectedCount'
    ));
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
