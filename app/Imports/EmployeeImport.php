<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Division;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class EmployeeImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // 1. CEK APAKAH EXCEL KOSONG?
        if ($rows->isEmpty()) {
            echo "❌ ERROR: File Excel TERBACA KOSONG! Cek isinya.\n";
            return;
        }

        echo "✅ File terbaca! Total Baris: " . $rows->count() . "\n";

        // Hapus Header
        $header = $rows->shift();
        echo "ℹ️ Header dibuang: " . json_encode($header) . "\n\n";

        $masuk = 0;
        $gagal = 0;

        foreach ($rows as $index => $row) {
            $nomorBaris = $index + 2;

            // ==========================================
            // DEBUGGING: TAMPILKAN BARIS PERTAMA SAJA
            // ==========================================
            if ($index === 0) {
                echo "🔍 CONTOH DATA BARIS PERTAMA (Index 0):\n";
                echo "   [0] NIK      : " . ($row[0] ?? 'KOSONG') . "\n";
                echo "   [1] NAMA     : " . ($row[1] ?? 'KOSONG') . "\n";
                echo "   [2] ORG      : " . ($row[2] ?? 'KOSONG') . "\n";
                echo "   [6] BRANCH   : " . ($row[6] ?? 'KOSONG') . "\n";
                echo "------------------------------------------------\n";
            }

            // 1. MAPPING KOLOM
            $nikRaw        = $row[0] ?? null;
            $nik = (string) $nikRaw;
            if ($nik && strlen($nik) < 4) {
                $nik = str_pad($nik, 4, '0', STR_PAD_LEFT);
                // Hasil: "807" -> "0807"
            }
            $nama       = $row[1] ?? null;
            $orgRaw     = $row[2] ?? '';
            $jabatan    = $row[3] ?? '';
            $level      = $row[4] ?? '';
            $status     = $row[5] ?? '';
            $branchRaw  = $row[6] ?? null;

            // 2. VALIDASI (PENYEBAB UTAMA DATA TIDAK MASUK)
            if (empty($nik) || empty($nama)) {
                echo "⚠️ Baris $nomorBaris DI-SKIP: NIK atau Nama Kosong. (Isi: " . json_encode($row) . ")\n";
                $gagal++;
                continue;
            }

            // 3. LOGIC DEPT & DIVISI
            $mapping = $this->mapStrictDepartment($branchRaw, $orgRaw, $jabatan);

            try {
                // Dept
                $department = Department::firstOrCreate(['name' => $mapping['dept']]);

                // Divisi
                $divisionId = null;
                if (!empty($mapping['divisi'])) {
                    $division = Division::firstOrCreate(
                        ['name' => $mapping['divisi'], 'department_id' => $department->id]
                    );
                    $divisionId = $division->id;
                }

                // 4. SIMPAN DATA
                Employee::updateOrCreate(
                    ['nik' => $nik],
                    [
                        'name'          => $nama,
                        'job_title'     => $jabatan,
                        'level'         => $level,
                        'organization'  => $orgRaw,
                        'status'        => $status,
                        'branch'        => $branchRaw ? trim($branchRaw) : null,
                        'department_id' => $department->id,
                        'division_id'   => $divisionId,
                    ]
                );
                $masuk++;
            } catch (\Exception $e) {
                echo "❌ ERROR SQL di Baris $nomorBaris: " . $e->getMessage() . "\n";
                $gagal++;
            }
        }

        echo "\n📊 LAPORAN AKHIR:\n";
        echo "✅ Berhasil Masuk: $masuk\n";
        echo "❌ Gagal/Skip    : $gagal\n";
    }

    // --- MAPPING FUNCTION (TIDAK SAYA UBAH) ---
    private function mapStrictDepartment($branch, $orgRaw, $jabatan)
    {
        $b = strtoupper(trim($branch));
        $o = strtoupper(trim($orgRaw));
        $j = strtoupper(trim($jabatan));
        $targetDiv = ucwords(strtolower(trim(str_replace(["\n", "\r"], ' ', $orgRaw))));

        if (str_contains($o, 'AUTOWIRE') || str_contains($j, 'AUTOWIRE')) return ['dept' => 'LOW VOLTAGE', 'divisi' => 'Plant A - Autowire'];
        if (str_contains($o, 'CCV') || str_contains($j, 'CCV')) return ['dept' => 'MEDIUM VOLTAGE', 'divisi' => 'Plant D - CCV'];
        if ($b === 'PLANT E' || str_contains($o, 'PLANT E')) return ['dept' => 'FIBER OPTIC', 'divisi' => $targetDiv];
        if ($b === 'PLANT A' || $b === 'PLANT C' || str_contains($o, 'PLANT A') || str_contains($o, 'PLANT C')) return ['dept' => 'LOW VOLTAGE', 'divisi' => $targetDiv];
        if ($b === 'PLANT B' || $b === 'PLANT D' || str_contains($o, 'PLANT B') || str_contains($o, 'PLANT D')) return ['dept' => 'MEDIUM VOLTAGE', 'divisi' => $targetDiv];

        if (str_contains($o, 'FINANCE') || str_contains($o, 'ACC') || str_contains($o, 'TAX')) return ['dept' => 'FINANCE & ACCOUNTING', 'divisi' => $targetDiv];
        if (str_contains($o, 'HC') || str_contains($o, 'HUMAN') || str_contains($o, 'HR')) return ['dept' => 'HUMAN CAPITAL', 'divisi' => $targetDiv];
        if (str_contains($o, 'FACILITY')) return ['dept' => 'FACILITY', 'divisi' => $targetDiv];
        if (str_contains($o, 'GA') || str_contains($o, 'GENERAL') || str_contains($o, 'AFFAIR')) return ['dept' => 'GENERAL AFFAIR', 'divisi' => $targetDiv];
        if (str_contains($o, 'IT') || str_contains($o, 'INFO') || str_contains($o, 'SYSTEM')) return ['dept' => 'INFORMATION TECHNOLOGY', 'divisi' => $targetDiv];
        if (str_contains($o, 'PROCURE') || str_contains($o, 'PURCH')) return ['dept' => 'PROCUREMENT', 'divisi' => $targetDiv];
        if (str_contains($o, 'MARKET')) return ['dept' => 'MARKETING', 'divisi' => $targetDiv];

        if (str_contains($o, 'SALES 1')) return ['dept' => 'SALES', 'divisi' => $targetDiv];
        if (str_contains($o, 'SALES 2')) return ['dept' => 'SALES 2', 'divisi' => $targetDiv];
        if (str_contains($o, 'COMMERCIAL') || str_contains($o, 'SUPPLY CHAIN')) return ['dept' => 'COMMERCIAL & SUPPLY CHAIN DIRECTOR', 'divisi' => $targetDiv];
        if (str_contains($o, 'SALES')) return ['dept' => 'SALES', 'divisi' => $targetDiv];

        return ['dept' => 'GENERAL AFFAIR', 'divisi' => $targetDiv];
    }
}
