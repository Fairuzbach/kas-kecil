<?php

namespace App\Livewire\PettyCash;

use Livewire\Component;
use App\Models\PettyCashRequest;
use App\Enums\PettyCashStatus;


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
            $nextStatus = \App\Enums\PettyCashStatus::PENDING_KLINIK;
            $message = 'Berhasil disetujui. Diteruskan ke Klink (Pak Nurtasa).';
        } else {
            $nextStatus = PettyCashStatus::PENDING_DIRECTOR;
            $message = 'Berhasil disetujui. Diteruskan ke Direktur.';
        }

        $this->request->update([
            'status' => $nextStatus,
            'manager_approved_at' => now(),
            'manager_id' => auth()->user(),
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

    public function approveKlinik()
    {
        if (auth()->user()->role !== 'klinik') {
            abort(403, 'Anda bukan petugas Klinik!');
        }

        if ($this->request->status->value !== 'pending_klinik') {
            $this->dispatch('notify', 'Status tidak valid untuk divalidasi.');
            return;
        }

        $this->request->update([
            'status' => \App\Enums\PettyCashStatus::PENDING_HC,
        ]);
        session()->flash('success', 'Validasi Medis Berhasil. Lanjut ke HC/Finance.');
    }

    public function approveHC()
    {
        if (strtolower(auth()->user()->role) !== 'hc') {
            abort(403, 'Akses Ditolak. Anda bukan HC.');
        }
        if ($this->request->status->value !== 'pending_hc') {
            $this->dispatch('notify', 'Status tiket tidak valid.');
            return;
        }
        $this->request->update([
            'status' => \App\Enums\PettyCashStatus::PENDING_FINANCE
        ]);
        session()->flash('success', 'Plafon disetujui. Tiket diteruskan ke Finance.');
    }

    public function approveFinance()
    {
        if (auth()->user()->role !== 'finance') {
            abort(403, 'Anda bukan Finance!');
        }

        if ($this->request->status->value !== 'pending_finance') {
            $this->dispatch('notify', 'Status tiket tidak valid untuk dicairkan.');
            return;
        }

        $this->request->update([
            'status' => \App\Enums\PettyCashStatus::PAID,
        ]);
        session()->flash('success', 'Dana Berhasil Dicairkan! Proses Selesai.');
    }
    public function render()
    {
        return view('livewire.petty-cash.show');
    }
}
