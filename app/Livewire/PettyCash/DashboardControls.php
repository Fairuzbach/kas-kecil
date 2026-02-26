<?php

namespace App\Livewire\PettyCash;

use App\Livewire\PettyCash\IndexTable;
use Livewire\Component;
use App\Models\Department;
use App\Exports\PettyCashExport;
use Maatwebsite\Excel\Facades\Excel;

class DashboardControls extends Component
{
    public $search = '';
    public $filterDept = 'all';
    public $filterStatus = 'all';

    // Kirim sinyal ke component tabel saat ada perubahan
    public function updated($property)
    {
        if (in_array($property, ['search', 'filterDept', 'filterStatus'])) {
            $this->dispatch('filter-updated', [
                'search' => $this->search,
                'filterDept' => $this->filterDept,
                'filterStatus' => $this->filterStatus,
            ])->to(IndexTable::class);
        }
    }

    public function exportExcel()
    {
        return Excel::download(
            new PettyCashExport($this->filterDept, $this->filterStatus, $this->search, auth()->user()),
            'report-petty-cash-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function render()
    {
        return view('livewire.petty-cash.dashboard-controls', [
            'departments' => Department::orderBy('name')->get(),
        ]);
    }
}
