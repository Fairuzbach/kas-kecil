<?php

namespace App\Exports;

use App\Models\PettyCashRequest;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PettyCashExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $dept, $status, $search, $user;

    public function __construct($dept, $status, $search, $user)
    {
        $this->dept = $dept;
        $this->status = $status;
        $this->search = $search;
        $this->user = $user;
    }

    public function query()
    {
        $query = PettyCashRequest::query()->with(['user', 'department']);

        // 1. Role Security
        if ($this->user->role === 'user') {
            $query->where('user_id', $this->user->id);
        } elseif ($this->user->role === 'manager') {
            $query->where('department_id', $this->user->department_id);
        }

        // 2. Apply Filters
        if ($this->dept !== 'all') {
            $query->where('department_id', $this->dept);
        }
        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        // 3. Apply Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('tracking_number', 'like', '%' . $this->search . '%')
                    ->orWhere('title', 'like', '%' . $this->search . '%');
            });
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return [
            'No. Tracking',
            'Tanggal',
            'Judul Pengajuan',
            'Pemohon',
            'Departemen',
            'Status',
            'Total Nominal',
        ];
    }

    public function map($row): array
    {
        return [
            $row->tracking_number,
            $row->created_at->format('d/m/Y'),
            $row->title,
            $row->user->name,
            $row->department->code ?? '-',
            strtoupper($row->status->value ?? $row->status),
            $row->amount,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Bold header
        ];
    }
}
