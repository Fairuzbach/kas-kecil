<?php

namespace App\Livewire\PettyCash;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\PettyCashRequest;
use Illuminate\Support\Facades\Auth;

class IndexTable extends Component
{
    use WithPagination;


    #[On('request-created')]
    public function refreshTable()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();

        // Pastikan memuat relasi 'approver' agar nama supervisor muncul di tabel
        $query = PettyCashRequest::query()
            ->with(['department', 'coa', 'user', 'approver']);

        if ($user->role === 'admin') {
            // Admin biasanya bisa melihat semua data (Jangan difilter user_id)
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('department_id', $user->department_id);
            });
        } elseif ($user->role === 'supervisor') {
            // SUPERVISOR: Lihat miliknya sendiri ATAU yang butuh dia approve
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('approver_id', $user->id); // Tiket yang menunjuk dia sebagai approver
            });
        } elseif ($user->role === 'manager') {
            $query->where(function ($q) use ($user) {

                // 1. Tiket milik sendiri (Bisa lihat SEMUA status, termasuk Draft)
                $q->where('user_id', $user->id)

                    // 2. ATAU Tiket staf departemen (Hanya status tertentu)
                    ->orWhere(function ($subQ) use ($user) {
                        $subQ->where('department_id', $user->department_id)
                            ->whereIn('status', [
                                \App\Enums\PettyCashStatus::PENDING_MANAGER, // Inbox (Perlu diapprove)
                                \App\Enums\PettyCashStatus::PENDING_DIRECTOR, // Monitoring
                                \App\Enums\PettyCashStatus::PENDING_FINANCE, // Monitoring
                                \App\Enums\PettyCashStatus::PAID, // History Selesai
                                \App\Enums\PettyCashStatus::REJECTED, // History Ditolak (Opsional)
                            ]);
                    });
            });
        } elseif ($user->role === 'director') {
            $query->where(function ($mainQuery) use ($user) {
                $mainQuery->where(function ($inbox) use ($user) {
                    $inbox->where('status', \App\Enums\PettyCashStatus::PENDING_DIRECTOR)
                        ->whereHas('department', function ($dept) use ($user) {
                            $dept->where('director_group', $user->director_group);
                        });
                })
                    ->orWhere(function ($history) use ($user) {
                        $history->whereNotNull('director_approved_at')
                            ->whereHas('department', function ($dept) use ($user) {
                                $dept->where('director_group', $user->director_group);
                            });
                    });
            });
        } elseif ($user->role === 'klinik') {
            $query->where('type', 'pengobatan')
                ->whereNotIn('status', [
                    \App\Enums\PettyCashStatus::DRAFT,
                    \App\Enums\PettyCashStatus::PENDING_MANAGER
                ]);
        } elseif ($user->role === 'hc') {
            $query->where('type', 'pengobatan')
                ->whereIn('status', [
                    \App\Enums\PettyCashStatus::PENDING_HC,
                    \App\Enums\PettyCashStatus::PENDING_SUPERVISOR,
                    \App\Enums\PettyCashStatus::PENDING_MANAGER,
                    \App\Enums\PettyCashStatus::PENDING_FINANCE,
                    \App\Enums\PettyCashStatus::PAID
                ]);
        } elseif ($user->role === 'finance') {
            $query->whereIn('status', [
                \App\Enums\PettyCashStatus::PENDING_FINANCE,
                \App\Enums\PettyCashStatus::PAID,
                \App\Enums\PettyCashStatus::REJECTED,
            ]);
        } else {
            // Role User Biasa
            $query->where('user_id', $user->id);
        }

        $requests = $query->latest()->paginate(10);

        return view('livewire.petty-cash.index-table', [
            'requests' => $requests
        ]);
    }
}
