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

    public function approveSupervisor()
    {
        if (auth()->id() !== $this->request->approver_id) {
            abort(403, 'Anda bukan approver yang dipilih oleh Requester.');
        }

        $this->request->update([
            'status' => PettyCashStatus::PENDING_MANAGER,
            'supervisor_approved_at' => now()
        ]);

        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text'  => 'Pengajuan akan diteruskan ke Manager.',
            'icon'  => 'success'
        ]);
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

        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text'  => $message,
            'icon'  => 'success'
        ]);
    }

    public function approveDirector()
    {
        $user = auth()->user();
        if (auth()->user()->role !== 'director') {
            abort(403, 'Akses ditolak');
        }

        if ($this->request->status->value !== 'pending_director') {
            $this->dispatch('swal', [
                'title' => 'Gagal',
                'text'  => 'Status tiket tidak valid.',
                'icon'  => 'error'
            ]);
            return;
        }

        $this->request->update([
            'status' => PettyCashStatus::PENDING_FINANCE,
            'director_approved_at' => now(),
            'director_id' => $user->id,
        ]);
        $this->request->refresh();
        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text'  => 'Berhasil approve, akan diteruskan ke tim FA',
            'icon'  => 'success'
        ]);
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
        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text'  => 'Berhasil approve, Akan diteruskan ke Tim HC',
            'icon'  => 'success'
        ]);
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
        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text'  => 'Berhasil Approve, akan diteruskan ke Tim FA',
            'icon'  => 'success'
        ]);
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
        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text'  => 'Dana berhasil dibayar/dicairkan!',
            'icon'  => 'success'
        ]);
    }
    public function render()
    {
        return view('livewire.petty-cash.show');
    }
}
