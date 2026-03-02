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
    public $showRevisionModal = false;
    public $revisionReason = '';
    public $items = [];

    public function mount(PettyCashRequest $pettyCashRequest)
    {
        $this->request = $pettyCashRequest->load(['details.coa', 'user', 'department']);
        $this->items = $this->request->details->toArray();
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
        $userEmployee = \App\Models\Employee::where('nik', $user->nik)->first();
        $userLevel = $userEmployee ? strtolower($userEmployee->level) : '';

        // Pastikan hanya staff finance (bukan manager) yang bisa akses
        if ($user->role !== 'finance' || $userLevel === 'manager') {
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
        $user = auth()->user();
        $userEmployee = \App\Models\Employee::where('nik', $user->nik)->first();
        $userLevel = $userEmployee ? strtolower($userEmployee->level) : '';
        if ($user->role !== 'finance' || $userLevel !== 'manager') {
            abort(403, 'Akses ditolak. Anda bukan Finance Manager!');
        }
        if ($this->request->status->value !== 'pending_finance_manager') {
            $this->dispatch('swal', [
                'title' => 'Gagal!',
                'text'  => 'Status tiket saat ini tidak valid untuk dicairkan.',
                'icon'  => 'error'
            ]);
            $this->showAcceptModal = false;
            return;
        }
        $this->request->status = \App\Enums\PettyCashStatus::PAID;
        $this->request->save();
        $this->showAcceptModal = false;
        session()->flash('success', 'Dana berhasil dibayar/dicairkan!');
        return redirect()->route('dashboard');
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

    public function confirmRevision()
    {
        $this->showRevisionModal = true;
    }

    public function requestRevision()
    {
        $this->validate([
            'revisionReason' => 'required|string|min:5',
        ], [
            'revisionReason.required' => 'Catatan revisi wajib diisi agar pemohon tahu apa yang harus diperbaiki.',
            'revisionReason.min' => 'Catatan revisi terlalu pendek (minimal 5 karakter).'
        ]);

        $this->request->status = \App\Enums\PettyCashStatus::REVISION;
        $this->request->rejected_by = auth()->id();
        $this->request->rejection_note = $this->revisionReason;

        $this->request->save();

        $this->showRevisionModal = false;
        $this->revisionReason = '';

        session()->flash('success', 'Pengajuan berhasil dikembalikan ke pemohon untuk direvisi.');
        return redirect()->route('dashboard');
    }

    public function resubmit()
    {
        if (auth()->id() !== $this->request->user_id || $this->request->status->value !== 'revision') {
            abort(403, 'Akses Ditolak. Anda tidak dapat mengedit pengajuan ini.');
        }

        $totalAmount = 0;

        foreach ($this->items as $itemData) {
            $detail = \App\Models\PettyCashDetail::find($itemData['id']);
            if ($detail) {
                $detail->update([
                    'item_name' => $itemData['item_name'],
                    'amount' => $itemData['amount'],
                ]);
                $totalAmount += $itemData['amount'];
            }
        }

        $nextStatus = $this->request->approver_id
            ? \App\Enums\PettyCashStatus::PENDING_SUPERVISOR
            : \App\Enums\PettyCashStatus::PENDING_MANGER;

        $this->request->update([
            'amount' => $totalAmount,
            'status' => $nextStatus,
            'supervisor_approved_at' => null,
            'manager_approved_at' => null,
            'director_approved_at' => null,
            'rejection_note' => null,
            'rejected_by' => null,
        ]);

        session()->flash('success', 'Pengajuan berhasil diperbarui dan diajukan ulang ke Supervisor');
        return redirect()->route('dashboard');
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
