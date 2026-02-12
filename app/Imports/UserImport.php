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
            // 1. BERSIH-BERSIH DATA (TRIM di awal biar aman)
            $nik        = trim($row['employee_id'] ?? '');
            $nama       = trim($row['full_name'] ?? '');
            $jabatan    = trim($row['job_position'] ?? $row['position'] ?? '');
            $orgRaw     = trim($row['organization'] ?? ''); // ✅ Wajib Trim
            $branchRaw  = trim($row['branch_name'] ?? '');

            // Validasi: Skip jika data tidak lengkap
            if (empty($nik) || empty($nama)) continue;

            // 2. FILTER USER
            if (!$this->shouldCreateUser($nama, $jabatan)) {
                continue;
            }

            // 3. TENTUKAN DEPARTEMEN & DIVISI
            $mapping = $this->mapDepartmentAndDivision($branchRaw, $orgRaw, $jabatan);

            $finalDeptName = $mapping['dept'];
            $finalDivName  = $mapping['divisi'];

            // 4. TENTUKAN GROUP DIREKTUR (Untuk Tabel Department)
            // Ini PENTING agar logic approval Direktur jalan
            $directorGroupForDept = $this->getDirectorGroupForDept($finalDeptName);

            // A. Simpan Departemen (Pakai updateOrCreate biar data group ter-update)
            $department = Department::updateOrCreate(
                ['name' => $finalDeptName],
                [
                    'code' => $this->generateDeptCode($finalDeptName),
                    'director_group' => $directorGroupForDept // ✅ Simpan Group ke Dept
                ]
            );

            // B. Simpan Divisi
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

            // 5. TENTUKAN ROLE USER
            $roleData = $this->determineRoleAndGroup($nama, $jabatan, $finalDeptName);

            // 6. SIMPAN USER
            $email = strtolower(str_replace(' ', '', $nik)) . "@jembo.com"; // Email dummy pakai NIK biar unik

            User::updateOrCreate(
                ['nik' => $nik],
                [
                    'name'            => $nama,
                    'email'           => $email,
                    'password'        => Hash::make('password123'), // Default password

                    'department_id'   => $department->id,
                    'division_id'     => $divisionId,
                    'branch'          => $branchRaw,
                    'phone_number'    => null,

                    'role'            => $roleData['role'],
                    // Ini group milik USER (misal dia Direktur Finance, groupnya finance)
                    'director_group'  => $roleData['director_group'],
                ]
            );
        }
    }

    /**
     * Logic Pemetaan Departemen
     */
    private function mapDepartmentAndDivision($branch, $orgRaw, $jabatan)
    {
        $b = strtoupper($branch);
        $o = strtoupper($orgRaw);
        $j = strtoupper($jabatan);

        // Default: Ambil nama organisasi yang sudah di-trim
        $dept = ucwords(strtolower($orgRaw));
        $div  = ucwords(strtolower($orgRaw));

        // --- LEVEL 1: CEK KEYWORD KHUSUS (Prioritas Tertinggi) ---
        // Penanganan kasus Maintenance, QC, QA agar tidak ketimpa Plant

        if (str_contains($o, 'MAINTENANCE') || str_contains($j, 'MAINTENANCE')) {
            return ['dept' => 'Maintenance', 'divisi' => $orgRaw];
        }
        if (str_contains($o, 'QC') || str_contains($j, 'QUALITY CONTROL')) {
            return ['dept' => 'Quality Control', 'divisi' => $orgRaw];
        }
        if (str_contains($o, 'QA') || str_contains($j, 'QUALITY ASSURANCE')) {
            return ['dept' => 'Quality Assurance', 'divisi' => $orgRaw];
        }

        // --- LEVEL 2: ATURAN PLANT (Branch) ---
        // Jika bukan Maintenance/QC, baru kita cek dia Plant mana

        if (str_contains($o, 'AUTOWIRE') || str_contains($j, 'AUTOWIRE')) {
            return ['dept' => 'Low Voltage', 'divisi' => 'Plant A - Autowire'];
        }
        if (str_contains($o, 'CCV') || str_contains($j, 'CCV')) {
            return ['dept' => 'Medium Voltage', 'divisi' => 'Plant D - CCV'];
        }

        if ($b === 'PLANT A' || $b === 'PLANT C') {
            return ['dept' => 'Low Voltage', 'divisi' => $orgRaw];
        }
        if ($b === 'PLANT B' || $b === 'PLANT D') {
            return ['dept' => 'Medium Voltage', 'divisi' => $orgRaw];
        }
        if ($b === 'PLANT E') {
            return ['dept' => 'Fiber Optic', 'divisi' => $orgRaw];
        }

        // --- LEVEL 3: FALLBACK HEAD OFFICE ---

        if (str_contains($o, 'FINANCE') || str_contains($o, 'ACCOUNTING')) {
            $dept = 'Finance & Accounting';
        } elseif (str_contains($o, 'HR') || str_contains($o, 'GA') || str_contains($o, 'GENERAL AFFAIR')) {
            $dept = 'HRGA';
        } elseif (str_contains($o, 'IT') || str_contains($o, 'INFORMATION')) {
            $dept = 'IT';
        } elseif (str_contains($o, 'COMMERCIAL') || str_contains($o, 'SALES') || str_contains($o, 'MARKETING')) {
            $dept = 'Commercial';
        } elseif (str_contains($o, 'PURCHASING') || str_contains($o, 'PROCUREMENT')) {
            $dept = 'Procurement'; // Atau masuk Commercial/Supply
        }

        return ['dept' => $dept, 'divisi' => $div];
    }

    /**
     * Menentukan Group Direktur untuk SEBUAH DEPARTEMEN
     * (Agar Direktur bisa melihat tiket dari dept ini)
     */
    private function getDirectorGroupForDept($deptName)
    {
        $d = strtoupper($deptName);

        // 1. Group FINANCE
        if (str_contains($d, 'FINANCE') || str_contains($d, 'ACCOUNTING') || str_contains($d, 'IT')) {
            return 'finance';
        }

        // 2. Group HC (Human Capital)
        if (str_contains($d, 'HR') || str_contains($d, 'GA') || str_contains($d, 'LEGAL') || str_contains($d, 'SECURITY')) {
            return 'hc';
        }

        // 3. Group COMMERCIAL & SUPPLY CHAIN
        if (
            str_contains($d, 'COMMERCIAL') || str_contains($d, 'SALES') || str_contains($d, 'MARKETING') ||
            str_contains($d, 'PROCUREMENT') || str_contains($d, 'PURCHASING') || str_contains($d, 'LOGISTIC') || str_contains($d, 'PPIC')
        ) {
            return 'commercial';
        }

        // 4. Group MANUFAKTUR (Operasional Pabrik)
        // Maintenance, Low Voltage, Medium Voltage, FO masuk sini
        if (
            str_contains($d, 'VOLTAGE') || str_contains($d, 'OPTIC') || str_contains($d, 'MAINTENANCE') ||
            str_contains($d, 'PRODUCTION') || str_contains($d, 'QUALITY') || str_contains($d, 'PLANT')
        ) {
            return 'manufaktur';
        }

        // Default (Misal Presdir lihat sisanya)
        return 'presdir';
    }

    // --- HELPER LAINNYA ---

    private function shouldCreateUser($nama, $jabatan)
    {
        $nama = strtolower($nama);
        $jabatan = strtolower($jabatan);

        if (str_contains($nama, 'alinda')) return true;
        if (str_contains($nama, 'nurtasa')) return true;

        $allowed = ['admin', 'supervisor', 'manager', 'direktur', 'director', 'head', 'chief', 'foreman'];
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

        // Special Users
        if (str_contains($nama, 'alinda')) return ['role' => 'hc', 'director_group' => null];
        if (str_contains($nama, 'nurtasa')) return ['role' => 'klinik', 'director_group' => null];

        // Directors
        if (str_contains($jabatan, 'direktur') || str_contains($jabatan, 'director')) {
            $role = 'director';
            $group = 'presdir'; // Default

            if (str_contains($jabatan, 'finance')) $group = 'finance';
            elseif (str_contains($jabatan, 'human') || str_contains($jabatan, 'hc') || str_contains($jabatan, 'hr')) $group = 'hc';
            elseif (str_contains($jabatan, 'comm') || str_contains($jabatan, 'supply')) $group = 'commercial';
            elseif (str_contains($jabatan, 'manu') || str_contains($jabatan, 'prod') || str_contains($jabatan, 'tech')) $group = 'manufaktur';
            elseif (str_contains($jabatan, 'pres') || str_contains($jabatan, 'utama')) $group = 'presdir';

            return ['role' => $role, 'director_group' => $group];
        }

        // Managers
        if (str_contains($jabatan, 'manager')) return ['role' => 'manager', 'director_group' => null];

        // Finance Staff
        if (str_contains($dept, 'finance') || str_contains($dept, 'acc')) {
            // Kecuali dia supervisor/manager, dia staff finance biasa
            return ['role' => 'finance', 'director_group' => null];
        }

        // Supervisor
        if (str_contains($jabatan, 'supervisor') || str_contains($jabatan, 'spv') || str_contains($jabatan, 'head')) {
            return ['role' => 'supervisor', 'director_group' => null];
        }

        return ['role' => 'staff', 'director_group' => null];
    }

    private function generateDeptCode($name)
    {
        return strtoupper(substr(str_replace([' ', '-', '&'], '', $name), 0, 3));
    }
}
