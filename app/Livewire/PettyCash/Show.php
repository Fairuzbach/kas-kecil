<?php

namespace App\Livewire\PettyCash;

use Livewire\Component;
use App\Models\PettyCashRequest;
use App\Enums\PettyCashStatus;
use Illuminate\Support\Facades\Auth;

class Show extends Component
{
    public PettyCashRequest $request;

    public function mount(PettyCashRequest $pettyCashRequest)
    {
        $this->request = $pettyCashRequest->load(['details.coa', 'user', 'department']);
    }

    public function approveManager()
    {
        if (auth()->user()->role !==  'manager') {
            abort(403);
        }

        if ($this->request->type->value === 'pengobatan') {
            $nextStatus = PettyCashStatus::PENDING_FINANCE;
        } else {
            $nextStatus = PettyCashStatus::PENDING_DIRECTOR;
        }

        $this->request->update([
            'status' => $nextStatus,
            'manager_approved_at' => now(),
        ]);

        session()->flash('success', 'Disetujui! Status berubah ke ' . $nextStatus->label());
    }

    public function approveDirector()
    {
        $user = auth()->user();

        $requiredGroup = $this->request->user->department->director_group;

        if ($user->director_group !== $requiredGroup) {
            $mapNames = [
                'manufacturing' => 'Manufacturing Director',
                'finance' => 'Finance Director',
                'president' => 'President Director',
                'hc' => 'Human Capital Director',
                'commercial' => 'Commercial Director',
            ];

            $bossName = $mapNames[$requiredGroup] ?? 'Direktur Terkait';

            session()->flash('error', "Anda tidak memiliki akses. Tiket ini wewenang $bossName.");
            return;
        }

        $this->request->update([
            'status' => PettyCashStatus::PENDING_FINANCE,
            'director_approved_at' => now(),
            'director_id' => $user->id,
        ]);

        session()->flash('success', 'Approval Direktur berhasil.');
    }


    public function render()
    {
        return view('livewire.petty-cash.show');
    }
}
