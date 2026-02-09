<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Department;
use App\Models\Division;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) return;

        foreach ($rows as $row) {
            // 1. BACA DATA DARI EXCEL
            $nik        = $row['employee_id'] ?? null;
            $nama       = $row['full_name'] ?? null;
            $jabatan    = $row['job_position'] ?? $row['position'] ?? '';
            $orgRaw     = $row['organization'] ?? '';

            // ✅ BRANCH: Ambil langsung dari Excel apa adanya
            $branchRaw  = $row['branch_name'] ?? null;

            // Validasi: Skip jika data tidak lengkap
            if (empty($nik) || empty($nama)) continue;

            // 2. FILTER USER: Hanya jabatan tertentu yang dibuatkan akun
            if (!$this->shouldCreateUser($nama, $jabatan)) {
                continue;
            }

            // 3. TENTUKAN DEPARTEMEN & DIVISI
            // (Menggunakan aturan Plant A -> Low Voltage, dst)
            $mapping = $this->mapDepartmentAndDivision($branchRaw, $orgRaw, $jabatan);

            $finalDeptName = $mapping['dept'];
            $finalDivName  = $mapping['divisi'];

            // A. Simpan/Cari Departemen
            $department = Department::firstOrCreate(
                ['name' => $finalDeptName],
                ['code' => $this->generateDeptCode($finalDeptName)]
            );

            // B. Simpan/Cari Divisi (Linked ke Dept tersebut)
            $divisionId = null;
            if (!empty($finalDivName)) {
                $division = Division::firstOrCreate(
                    [
                        'name' => $finalDivName,
                        'department_id' => $department->id
                    ]
                );
                $divisionId = $division->id;
            }

            // 4. TENTUKAN ROLE (Akses Login)
            $roleData = $this->determineRoleAndGroup($nama, $jabatan, $finalDeptName);

            // 5. GENERATE EMAIL
            $email = "{$nik}@jembo.com";

            // 6. SIMPAN USER KE DATABASE
            User::updateOrCreate(
                ['nik' => $nik],
                [
                    'name'           => $nama,
                    'email'          => $email,
                    'password'       => Hash::make('password123'),

                    'department_id'  => $department->id,
                    'division_id'    => $divisionId,

                    'branch'         => $branchRaw, // ✅ Disimpan sesuai Excel
                    'phone_number'   => null,

                    'role'           => $roleData['role'],
                    'director_group' => $roleData['director_group'],
                ]
            );
        }
    }

    /**
     * Logic Pemetaan Departemen sesuai request (Plant A -> Low Voltage)
     */
    private function mapDepartmentAndDivision($branch, $orgRaw, $jabatan)
    {
        $b = strtoupper(trim($branch));
        $o = strtoupper(trim($orgRaw));
        $j = strtoupper(trim($jabatan));

        // Default: Ambil nama organisasi dari Excel
        $dept = ucwords(strtolower($orgRaw));
        $div  = ucwords(strtolower($orgRaw));

        // ATURAN 1: Keyword Khusus (Autowire & CCV)
        if (str_contains($o, 'AUTOWIRE') || str_contains($j, 'AUTOWIRE')) {
            return ['dept' => 'Low Voltage', 'divisi' => 'Plant A - Autowire'];
        }
        if (str_contains($o, 'CCV') || str_contains($j, 'CCV')) {
            return ['dept' => 'Medium Voltage', 'divisi' => 'Plant D - CCV'];
        }

        // ATURAN 2: Berdasarkan Plant (Branch)
        if ($b === 'PLANT A' || $b === 'PLANT C') {
            return ['dept' => 'Low Voltage', 'divisi' => ucwords(strtolower($orgRaw))];
        }
        if ($b === 'PLANT B' || $b === 'PLANT D') {
            return ['dept' => 'Medium Voltage', 'divisi' => ucwords(strtolower($orgRaw))];
        }
        if ($b === 'PLANT E') {
            return ['dept' => 'Fiber Optic', 'divisi' => ucwords(strtolower($orgRaw))];
        }

        // ATURAN 3: Fallback untuk Head Office (Finance, HRGA, dll)
        if (str_contains($o, 'FINANCE') || str_contains($o, 'ACCOUNTING')) {
            $dept = 'Finance & Accounting';
        } elseif (str_contains($o, 'HR') || str_contains($o, 'GA')) {
            $dept = 'HRGA';
        } elseif (str_contains($o, 'IT') || str_contains($o, 'INFORMATION')) {
            $dept = 'IT';
        } elseif (str_contains($o, 'COMMERCIAL') || str_contains($o, 'SALES')) {
            $dept = 'Commercial';
        }

        return ['dept' => $dept, 'divisi' => $div];
    }

    // --- HELPER LAINNYA ---

    private function shouldCreateUser($nama, $jabatan)
    {
        $nama = strtolower($nama);
        $jabatan = strtolower($jabatan);

        // Filter Nama Spesifik
        if (str_contains($nama, 'alinda')) return true;
        if (str_contains($nama, 'nurtasa')) return true;

        // Filter Jabatan
        $allowed = ['admin', 'supervisor', 'manager', 'direktur', 'director', 'head'];
        foreach ($allowed as $role) {
            if (str_contains($jabatan, $role)) return true;
        }
        return false;
    }

    private function determineRoleAndGroup($nama, $jabatan, $deptName)
    {
        $nama = strtolower($nama);
        $jabatan = strtolower($jabatan);
        $dept = strtolower($deptName);

        if (str_contains($nama, 'alinda')) return ['role' => 'hc', 'director_group' => null];
        if (str_contains($nama, 'nurtasa')) return ['role' => 'klinik', 'director_group' => null];

        if (str_contains($jabatan, 'direktur') || str_contains($jabatan, 'director')) {
            $role = 'director';
            if (str_contains($dept, 'finance')) $group = 'finance';
            elseif (str_contains($dept, 'hr') || str_contains($dept, 'ga') || str_contains($dept, 'comm')) $group = 'commercial';
            else $group = 'operation';
            return ['role' => $role, 'director_group' => $group];
        }

        if (str_contains($jabatan, 'manager')) return ['role' => 'manager', 'director_group' => null];

        // Staff Finance tetap diberi role finance
        if (str_contains($dept, 'finance') || str_contains($dept, 'acc')) {
            return ['role' => 'finance', 'director_group' => null];
        }

        return ['role' => 'staff', 'director_group' => null];
    }

    private function generateDeptCode($name)
    {
        return strtoupper(substr(str_replace([' ', '-', '&'], '', $name), 0, 3));
    }
}
