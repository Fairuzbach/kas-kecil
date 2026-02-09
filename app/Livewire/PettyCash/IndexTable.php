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

        $query = PettyCashRequest::query()
            ->with(['department', 'coa', 'user']);


        if (in_array($user->role, ['admin', 'supervisor'])) {
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'manager') {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('department_id', $user->department_id);
            });
        } elseif ($user->role === 'director') {

            $query->whereHas('department', function ($q) use ($user) {
                $q->where('director_group', $user->director_group);
            });
        } elseif ($user->role === 'klinik') {

            $query->where('type', 'pengobatan');


            $query->where('status', '!=', \App\Enums\PettyCashStatus::DRAFT);


            $query->where('status', '!=', \App\Enums\PettyCashStatus::PENDING_MANAGER);
        } elseif ($user->role === 'hc') {

            $query->where('type', 'pengobatan');


            $query->whereIn('status', [
                \App\Enums\PettyCashStatus::PENDING_HC,      // Inbox Alinda
                \App\Enums\PettyCashStatus::PENDING_FINANCE, // History (Menunggu bayar)
                \App\Enums\PettyCashStatus::PAID             // History (Selesai)
            ]);
        } elseif (in_array($user->role, ['finance', 'admin'])) {
            $query->whereIn('status', [

                \App\Enums\PettyCashStatus::PENDING_FINANCE, // History (Menunggu bayar)
                \App\Enums\PettyCashStatus::PAID, // History (Menunggu bayar)
                \App\Enums\PettyCashStatus::REJECTED, // History (Menunggu bayar)
            ]);
        } else {
            $query->where('user_id', $user->id);
        }


        $requests = $query->latest()->paginate(10);

        return view('livewire.petty-cash.index-table', [
            'requests' => $requests
        ]);
    }
}
