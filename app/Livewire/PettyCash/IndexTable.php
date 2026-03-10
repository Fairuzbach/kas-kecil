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

    public $search = '';
    public $filterDept = 'all';
    public $filterStatus = 'all';

    #[On('filter-updated')]
    public function updateFilters($data)
    {

        $this->search = $data['search'];
        $this->filterDept = $data['filterDept'];
        $this->filterStatus = $data['filterStatus'];
        $this->resetPage();
    }
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
                                \App\Enums\PettyCashStatus::PENDING_MANAGER,
                                \App\Enums\PettyCashStatus::PENDING_DIRECTOR,
                                \App\Enums\PettyCashStatus::PENDING_FINANCE,
                                \App\Enums\PettyCashStatus::PENDING_FINANCE_MANAGER,
                                \App\Enums\PettyCashStatus::READY_FOR_INFOR,
                                \App\Enums\PettyCashStatus::PAID,
                                \App\Enums\PettyCashStatus::REJECTED,
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
                    \App\Enums\PettyCashStatus::PENDING_FINANCE_MANAGER,
                    \App\Enums\PettyCashStatus::READY_FOR_INFOR,
                    \App\Enums\PettyCashStatus::PAID
                ]);
        } elseif ($user->role === 'finance') {
            if (strtolower($user->level) === 'manager') {
                $query->whereIn('status', [
                    \App\Enums\PettyCashStatus::PENDING_FINANCE_MANAGER,
                    \App\Enums\PettyCashStatus::PENDING_FINANCE,
                    \App\Enums\PettyCashStatus::READY_FOR_INFOR,
                    \App\Enums\PettyCashStatus::PAID,
                    \App\Enums\PettyCashStatus::REJECTED,
                ]);
            } else {
                $query->whereIn('status', [
                    \App\Enums\PettyCashStatus::PENDING_FINANCE,
                    \App\Enums\PettyCashStatus::PENDING_FINANCE_MANAGER,
                    \App\Enums\PettyCashStatus::READY_FOR_INFOR,
                    \App\Enums\PettyCashStatus::PAID,
                    \App\Enums\PettyCashStatus::REJECTED,
                ]);
            }
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('tracking_number', 'like', '%' . $this->search . '%')
                    ->orWhere('title', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($u) {
                        $u->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }
        if ($this->filterDept !== 'all') {
            $query->where('department_id', $this->filterDept);
        }
        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        $requests = $query->latest()->paginate(10);

        return view('livewire.petty-cash.index-table', [
            'requests' => $requests
        ]);
    }

    public function exportToInfor()
    {
        $request = PettyCashRequest::with(['details.coa', 'department'])
            ->where('status', \App\Enums\PettyCashStatus::READY_FOR_INFOR)
            ->get();

        if ($request->isEmpty()) {
            session()->flash('error', 'Tidak ada data yang Siap Diupload Ke INFOR!');
            return;
        }

        $fileName = 'INFOR_Upload_' . date('Ymd_His') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no_cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Tanggal', 'Ref_No', 'Account_Code', 'Dept_Code', 'Description', 'Amount', 'D_C'];
        $callback = function () use ($request, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($request as $req) {
                foreach ($req->details as $details) {
                    $row = [
                        $req->created_at->format('Y-m-d'),
                        $req->tracking_number,
                        $details->coa ? $details->coa->code : '',
                        $req->department ? $req->department->code : '',
                        $details->item_name,
                        $details->amount,
                        'D'
                    ];
                    fputcsv($file, $row);
                }
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}
