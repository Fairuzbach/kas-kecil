<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use App\Models\PettyCashRequest;
use App\Models\PettyCashDetail;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $department_id = 'all';
    public $period = 'monthly';
    public $year;

    public function mount()
    {
        $this->year = date('Y');
    }

    public function updatedYear()
    {
        $this->refreshChart();
    }

    // 2. Saat Periode Berubah
    public function updatedPeriod()
    {
        $this->refreshChart();
    }

    // 3. Saat Departemen Berubah
    public function updatedDepartmentId()
    {
        $this->refreshChart();
    }

    // Function Pusat untuk Kirim Event
    public function refreshChart()
    {
        $this->dispatch(
            'update-chart',
            trend: $this->getTrendData(),
            coa: $this->getCoaData()
        );
    }

    public function render()
    {
        return view('livewire.finance.dashboard', [
            'departments' => Department::all(),
            'totalExpense' => $this->calculateTotal(),
            'trendData' => $this->getTrendData(),
            'coaData' => $this->getCoaData(),
            'highestSpender' => $this->getHighestSpender(),
        ])->layout('layouts.app');
    }
    private function getHighestSpender()
    {
        return PettyCashRequest::query()
            ->where('status', 'paid')
            ->whereYear('created_at', $this->year)
            ->selectRaw('department_id, SUM(amount) as total_amount')
            ->groupBy('department_id')
            ->orderByDesc('total_amount')
            ->with('department')
            ->first();
    }

    // --- LOGIC DATA TREND ---
    private function getTrendData()
    {
        $query = $this->getBaseQuery();

        switch ($this->period) {
            case 'weekly':
                $query->selectRaw('WEEK(created_at) as label, SUM(amount) as total')->groupBy('label');
                break;
            case 'monthly':
                $query->selectRaw('MONTH(created_at) as label, SUM(amount) as total')->groupBy('label');
                break;
            case 'quarterly':
                $query->selectRaw('QUARTER(created_at) as label, SUM(amount) as total')->groupBy('label');
                break;
            case 'semester':
                $query->selectRaw('CEIL(MONTH(created_at) / 6) as label, SUM(amount) as total')->groupBy('label');
                break;
            case 'yearly':
                $query->selectRaw('YEAR(created_at) as label, SUM(amount) as total')->groupBy('label');
                break;
        }

        $results = $query->orderBy('label')->get();

        $labels = $results->map(fn($row) => $this->formatLabel($row->label));
        $totals = $results->pluck('total');

        return ['labels' => $labels, 'totals' => $totals];
    }

    // --- LOGIC DATA COA ---
    private function getCoaData()
    {
        // Kita query ke tabel DETAIL, tapi filter berdasarkan kondisi HEADER (Request)
        $query = PettyCashDetail::query()
            ->whereHas('request', function ($q) {
                // Filter Parent (Request)
                $q->where('status', 'paid') // Hanya yang sudah dibayar
                    ->whereYear('created_at', $this->year);

                // Filter Department (jika ada)
                if ($this->department_id !== 'all') {
                    $q->where('department_id', $this->department_id);
                }
            });

        // Grouping & Summing
        $results = $query->selectRaw('coa_id, SUM(amount) as total')
            ->with('coa') // Eager load nama COA
            ->groupBy('coa_id')
            ->orderByDesc('total')
            ->get();

        // --- LOGIC TOP 10 + OTHERS (Sama seperti sebelumnya) ---
        $topLimit = 10;
        $topData = $results->take($topLimit);
        $othersTotal = $results->skip($topLimit)->sum('total');

        $labels = [];
        $totals = [];

        foreach ($topData as $row) {
            if ($row->coa) {
                $labels[] = $row->coa->code . ' - ' . $row->coa->name;
            } else {
                $labels[] = 'Tanpa COA';
            }

            $totals[] = (int) $row->total;
        }

        if ($othersTotal > 0) {
            $labels[] = 'Lain-lain (' . ($results->count() - $topLimit) . ' Akun)';
            $totals[] = (int) $othersTotal;
        }

        return [
            'labels' => $labels,
            'totals' => $totals
        ];
    }

    private function getBaseQuery()
    {
        $q = PettyCashRequest::query()
            ->where('status', 'paid') // Hanya yang paid
            ->whereYear('created_at', $this->year);

        if ($this->department_id !== 'all') {
            $q->where('department_id', $this->department_id);
        }
        return $q;
    }

    private function calculateTotal()
    {
        return $this->getBaseQuery()->sum('amount');
    }

    private function formatLabel($value)
    {
        if ($this->period === 'weekly') return 'Minggu ke-' . $value;
        if ($this->period === 'monthly') return Carbon::create()->month($value)->format('F');
        if ($this->period === 'quarterly') return 'Kuartal ' . $value;
        if ($this->period === 'semester') return 'Semester ' . $value;
        return 'Tahun ' . $value;
    }
}
