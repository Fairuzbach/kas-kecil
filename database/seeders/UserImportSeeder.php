<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserImportSeeder extends Seeder
{
    public function run()
    {
        // ==========================================================
        // 1. AMBIL KARYAWAN PENTING (YANG PERLU LOGIN)
        // ==========================================================
        $employees = Employee::where(function ($query) {
            $query->where('level', 'like', '%manager%')
                ->orWhere('level', 'like', '%director%')
                ->orWhere('level', 'like', '%supervisor%') // Ambil SPV
                ->orWhere('level', 'like', '%head%')
                ->orWhere('job_title', 'like', '%admin%')   // Ambil Admin
                ->orWhere('name', 'like', '%alinda%')
                ->orWhere('name', 'like', '%nurtasa%')
                ->orWhere('name', 'like', '%tristana%');
        })->get();

        $this->command->info("🔄 Memproses " . $employees->count() . " karyawan...");

        foreach ($employees as $emp) {

            // LOGIKA BARU: Kita kirim juga parameter Level agar deteksi lebih akurat
            $roleData = $this->determineRole(
                $emp->name,
                $emp->job_title,
                $emp->level,       // <--- Parameter Baru
                $emp->department->name ?? ''
            );

            $slugNama = Str::slug(explode(' ', trim($emp->name))[0]);
            $email = "{$slugNama}.{$emp->nik}@jembo.com";

            User::updateOrCreate(
                ['nik' => $emp->nik],
                [
                    'name'           => $emp->name,
                    'email'          => $email,
                    'password'       => Hash::make('password123'),
                    'department_id'  => $emp->department_id,
                    'division_id'    => $emp->division_id,
                    'branch'         => $emp->branch,
                    'role'           => $roleData['role'], // <--- Role sudah spesifik
                    'director_group' => $roleData['director_group'],
                ]
            );
        }

        $this->command->info("✅ Sukses generate User!");
        $this->command->newLine();

        // ==========================================================
        // 2. CEK KELENGKAPAN STRUKTUR
        // ==========================================================
        $this->checkStructuralCompleteness();
    }

    /**
     * Menentukan Role secara Spesifik
     */
    private function determineRole($nama, $jabatan, $level, $deptName)
    {
        $nama    = strtolower($nama ?? '');
        $jabatan = strtolower($jabatan ?? '');
        $level   = strtolower($level ?? ''); // Data level dari excel
        $dept    = strtolower($deptName ?? '');

        // 1. SPECIAL USERS
        if (str_contains($nama, 'alinda')) return ['role' => 'hc', 'director_group' => null];
        if (str_contains($nama, 'nurtasa')) return ['role' => 'klinik', 'director_group' => null];
        if (str_contains($nama, 'tristana')) return ['role' => 'admin', 'director_group' => null];

        // 2. DIRECTORS
        if (str_contains($jabatan, 'direktur') || str_contains($jabatan, 'director') || str_contains($level, 'director')) {
            $role = 'director';
            // Tentukan Group Director
            if (str_contains($dept, 'finance')) $group = 'finance';
            elseif (str_contains($dept, 'hr') || str_contains($dept, 'comm') || str_contains($dept, 'sales')) $group = 'commercial';
            else $group = 'operation';

            return ['role' => $role, 'director_group' => $group];
        }

        // 3. MANAGERS (Termasuk Senior Manager, GM)
        if (str_contains($jabatan, 'manager') || str_contains($level, 'manager')) {
            return ['role' => 'manager', 'director_group' => null];
        }

        // 4. SUPERVISORS (Termasuk SPV, Section Head)
        // Jika jabatan atau level mengandung kata 'supervisor' atau 'spv'
        if (str_contains($jabatan, 'supervisor') || str_contains($level, 'supervisor') || str_contains($jabatan, 'spv')) {
            return ['role' => 'supervisor', 'director_group' => null];
        }

        // 5. ADMINS
        if (str_contains($jabatan, 'admin') || str_contains($level, 'admin')) {
            return ['role' => 'admin', 'director_group' => null];
        }

        // 6. FINANCE STAFF (Khusus Staff biasa di dept Finance)
        // Jika dia Admin/SPV Finance, dia akan kena filter di atas duluan.
        // Jika lolos sampai sini, berarti dia Staff biasa di Finance.
        if (str_contains($dept, 'finance') || str_contains($dept, 'accounting')) {
            return ['role' => 'finance', 'director_group' => null];
        }

        // 7. STAFF BIASA
        return ['role' => 'staff', 'director_group' => null];
    }

    private function checkStructuralCompleteness()
    {
        $this->command->warn("📊 ANALISA STRUKTUR ORGANISASI:");

        $departments = Department::with('employees')->get();
        $reportData = [];

        foreach ($departments as $dept) {
            // Logic pengecekan ini melihat data karyawan (Excel)
            $hasManager = $dept->employees->filter(fn($e) => str_contains(strtolower($e->job_title . $e->level), 'manager') || str_contains(strtolower($e->job_title . $e->level), 'head'))->count();
            $hasSpv     = $dept->employees->filter(fn($e) => str_contains(strtolower($e->job_title . $e->level), 'supervisor') || str_contains(strtolower($e->job_title . $e->level), 'spv'))->count();
            $hasAdmin   = $dept->employees->filter(fn($e) => str_contains(strtolower($e->job_title . $e->level), 'admin'))->count();

            if (($hasManager == 0 || $hasSpv == 0 || $hasAdmin == 0) && !str_contains(strtolower($dept->name), 'director')) {
                $reportData[] = [
                    'Department' => $dept->name,
                    'Manager'    => $hasManager ? '✅' : '❌',
                    'Supervisor' => $hasSpv ? '✅' : '❌',
                    'Admin'      => $hasAdmin ? '✅' : '❌',
                ];
            }
        }

        if (count($reportData) > 0) {
            $this->command->table(['Department', 'MGR', 'SPV', 'ADM'], $reportData);
        } else {
            $this->command->info("🎉 Struktur Lengkap!");
        }
    }
}
