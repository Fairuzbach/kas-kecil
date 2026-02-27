<?php

namespace App\Livewire\PettyCash;

use Livewire\Component;
use App\Models\PettyCashRequest;
use App\Enums\PettyCashStatus;
use Illuminate\Support\Facades\Auth;

class Show extends Component
{
    public PettyCashRequest $request;
    public $showRejectModal = false;
    public $showAcceptModal = false;
    public $rejectionReason = '';

    public function mount(PettyCashRequest $pettyCashRequest)
    {
        $this->request = $pettyCashRequest->load(['details.coa', 'user', 'department']);
    }

    public function updateCoa($detailId, $newCoaId)
    {
        if (!in_array(auth()->user()->role, ['cashier', 'finance'])) {
            abort(403, 'Akses ditolak. Hanya Cashier atau Finance yang dapat mengubah COA.');
        }

        $detail = \App\Models\PettyCashDetail::find($detailId);
        if ($detail) {
            $detail->update(['coa_id' => $newCoaId]);

            $this->request->refresh();
            $this->dispatch('swal', [
                'title' => 'Berhasil!',
                'text'  => 'COA berhasil diperbarui.',
                'icon'  => 'success'
            ]);
        }
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
    public function verifyCoa()
    {
        $user = auth()->user();

        // Pastikan hanya staff finance (bukan manager) yang bisa akses
        if ($user->role !== 'finance' || strtolower($user->level) === 'manager') {
            abort(403, 'Akses ditolak.');
        }

        // Ubah status ke Manager
        $this->request->status = \App\Enums\PettyCashStatus::PENDING_FINANCE_MANAGER;
        $this->request->save();

        session()->flash('success', 'COA berhasil diverifikasi dan diteruskan ke Finance Manager.');
        return redirect()->route('dashboard');
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

    public function confirmReject()
    {
        $this->showRejectModal = true;
    }
    public function reject()
    {
        $this->validate([
            'rejectionReason' => 'required|string|min:5',
        ]);

        // CARA MANUAL (MENGHINDARI ISU FILLABLE)
        $this->request->status = \App\Enums\PettyCashStatus::REJECTED;
        $this->request->rejected_by = auth()->id();
        $this->request->rejection_note = $this->rejectionReason;

        // Simpan perubahan
        $this->request->save();

        $this->showRejectModal = false;
        $this->rejectionReason = '';

        session()->flash('success', 'Pengajuan berhasil ditolak.');
    }
    public function render()
    {
        $departmentCoas = \App\Models\Coa::select('coas.*')
            ->join('coa_department', 'coas.id', '=', 'coa_department.coa_id')
            ->where('coa_department.department_id', $this->request->department_id)
            ->orderBy('coas.code', 'asc')
            ->get();
        return view('livewire.petty-cash.show', [
            'departmentCoas' => $departmentCoas
        ]);
    }
}
