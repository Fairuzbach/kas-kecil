<?php

namespace App\Livewire\PettyCash;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PettyCashRequest;
use Illuminate\Support\Facades\Auth;

class IndexTable extends Component
{
    use WithPagination;

    public function render()
    {
        $user = Auth::user();

        // Query Dasar
        $query = PettyCashRequest::query()
            ->with(['department', 'coa', 'user']); // Mencegah N+1

        // Logika Filter Berdasarkan Role:
        // 1. Staff: Hanya melihat punya sendiri
        if ($user->role === 'staff') {
            $query->where('user_id', $user->id);
        }
        // 2. Manager: Melihat punya sendiri DAN pengajuan dari staff departemennya
        elseif ($user->role === 'manager') {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id) // Punya sendiri
                    ->orWhere('department_id', $user->department_id); // Punya anak buah
            });
        }
        // 3. Finance / Director: Bisa melihat semua (atau filter nanti)
        // (Biarkan default query mengambil semua)

        $requests = $query->latest()->paginate(10);

        return view('livewire.petty-cash.index-table', [
            'requests' => $requests
        ]);
    }
}
