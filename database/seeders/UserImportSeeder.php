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
        // 1. AMBIL DATA KARYAWAN (EAGER LOADING)
        // ==========================================================
        $employees = Employee::with(['department', 'division'])
            ->where(function ($query) {
                // Filter Jabatan Penting
                $query->where('level', 'like', '%manager%')
                    ->orWhere('level', 'like', '%director%')
                    ->orWhere('level', 'like', '%supervisor%')
                    ->orWhere('level', 'like', '%head%')
                    ->orWhere('job_title', 'like', '%admin%')

                    // Filter Nama Spesifik (Hardcoded)
                    ->orWhere('name', 'like', '%alinda%')
                    ->orWhere('name', 'like', '%nurtasa%')
                    ->orWhere('name', 'like', '%sisilia%')
                    ->orWhere('name', 'like', '%tristana%')
                    ->orWhere('name', 'like', '%mary%')
                    ->orWhere('name', 'like', '%bambang pramadi%');
            })->get();

        $count = $employees->count();
        $this->command->info("🔄 Memproses {$count} karyawan...");

        // Header Tabel Debug
        $debugAnomalies = [];
        $progressBar = $this->command->getOutput()->createProgressBar($count);
        $progressBar->start();

        foreach ($employees as $emp) {

            // 1. Tentukan Role & Group
            $roleData = $this->determineRole(
                $emp->name,
                $emp->job_title,
                $emp->level,
                $emp->department->name ?? ''
            );

            // --- 🛑 DEBUGGER LOGIC: CEK ANOMALI MAINTENANCE ---
            // Kita cek: Jika jabatannya Maintenance, tapi Groupnya BUKAN Manufaktur -> Catat!
            $isMaintenanceOrPlant = str_contains(strtolower($emp->job_title ?? ''), 'maintenance') ||
                str_contains(strtolower($emp->department->name ?? ''), 'maintenance') ||
                str_contains(strtolower($emp->job_title ?? ''), 'plant');

            if ($isMaintenanceOrPlant && $roleData['director_group'] !== 'manufaktur' && $roleData['role'] !== 'director') {
                $debugAnomalies[] = [
                    'Name' => Str::limit($emp->name, 20),
                    'Job' => Str::limit($emp->job_title, 20),
                    'Dept' => Str::limit($emp->department->name ?? '-', 20),
                    'Assigned Group' => $roleData['director_group'] ?? 'NULL', // <--- INI YG SALAH
                    'Role' => $roleData['role']
                ];
            }
            // ---------------------------------------------------

            // 2. Generate Email
            $firstName = explode(' ', trim($emp->name))[0];
            $slugNama = Str::slug($firstName);
            $email = strtolower("{$slugNama}.{$emp->nik}@jembo.com");

            // 3. Simpan User
            User::updateOrCreate(
                ['nik' => $emp->nik],
                [
                    'name'            => $emp->name,
                    'email'           => $email,
                    'password'        => Hash::make('password123'),
                    'department_id'   => $emp->department_id,
                    'division_id'     => $emp->division_id,
                    'branch'          => $emp->branch,
                    'phone_number'    => $emp->phone ?? null,

                    'role'            => $roleData['role'],
                    'director_group'  => $roleData['director_group'],
                ]
            );

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->newLine(2);

        // ==========================================================
        // LAPORAN DEBUG (Hanya muncul jika ada yang salah)
        // ==========================================================
        if (count($debugAnomalies) > 0) {
            $this->command->error("⚠️  PERINGATAN: Ditemukan " . count($debugAnomalies) . " orang Operasional/Maintenance yang 'Nyasar' Group:");
            $this->command->table(
                ['Nama', 'Jabatan Asli', 'Dept Asli', 'Masuk Group (Salah)', 'Role'],
                $debugAnomalies
            );
            $this->command->line("👉 Saran: Cek logic 'determineRole' bagian Manufaktur.");
        } else {
            $this->command->info("✅ Mantap! Tidak ada orang Maintenance yang nyasar ke Group lain.");
        }

        $this->command->newLine();

        // ==========================================================
        // 2. CEK KELENGKAPAN STRUKTUR
        // ==========================================================
        $this->checkStructuralCompleteness();
    }

    /**
     * LOGIC PENENTUAN ROLE & GROUP
     */
    private function determineRole($nama, $jabatan, $level, $deptName)
    {
        $nama    = strtolower($nama ?? '');
        $jabatan = strtolower($jabatan ?? '');
        $level   = strtolower($level ?? '');
        $dept    = strtolower($deptName ?? '');

        // --- 1. SPECIAL USERS ---
        if (str_contains($nama, 'alinda')) return ['role' => 'hc', 'director_group' => null];
        if (str_contains($nama, 'nurtasa')) return ['role' => 'klinik', 'director_group' => null];
        if (str_contains($nama, 'tristana')) return ['role' => 'admin', 'director_group' => null];
        if (str_contains($nama, 'sisilia')) return ['role' => 'finance', 'director_group' => null];

        // Director Mapping
        if (str_contains($nama, 'mary')) return ['role' => 'director', 'director_group' => 'presdir'];
        if (str_contains($nama, 'bambang pramadi')) return ['role' => 'director', 'director_group' => 'manufaktur'];

        // --- 2. DIRECTORS (JABATAN) ---
        if (str_contains($jabatan, 'direktur') || str_contains($jabatan, 'director') || str_contains($level, 'director')) {
            $role = 'director';

            if (str_contains($dept, 'finance') || str_contains($dept, 'acc')) {
                $group = 'finance';
            } elseif (str_contains($dept, 'hr') || str_contains($dept, 'comm') || str_contains($dept, 'sales') || str_contains($dept, 'procure')) {
                $group = 'commercial';
            } elseif (str_contains($dept, 'hc') || str_contains($dept, 'human')) {
                $group = 'hc';
            } else {
                $group = 'manufaktur';
            }
            return ['role' => $role, 'director_group' => $group];
        }

        // --- 3. MANAGERS ---
        if (str_contains($jabatan, 'manager') || str_contains($level, 'manager')) {
            return ['role' => 'manager', 'director_group' => null];
        }

        // --- 4. SUPERVISORS ---
        if (
            str_contains($jabatan, 'supervisor') || str_contains($level, 'supervisor') ||
            str_contains($jabatan, 'spv') || str_contains($jabatan, 'section head')
        ) {
            return ['role' => 'supervisor', 'director_group' => null];
        }

        // --- 5. ADMINS ---
        if (str_contains($jabatan, 'admin') || str_contains($level, 'admin')) {
            return ['role' => 'admin', 'director_group' => null];
        }

        // --- 6. FINANCE STAFF ---
        if (str_contains($dept, 'finance') || str_contains($dept, 'accounting')) {
            return ['role' => 'finance', 'director_group' => null];
        }

        // --- 7. STAFF BIASA ---
        return ['role' => 'staff', 'director_group' => null];
    }

    private function checkStructuralCompleteness()
    {
        $this->command->warn("📊 ANALISA STRUKTUR ORGANISASI:");

        $departments = Department::with('employees')->get();
        $reportData = [];

        foreach ($departments as $dept) {
            if ($dept->employees->isEmpty() || str_contains(strtolower($dept->name), 'director')) continue;

            $employees = $dept->employees;

            $hasManager = $employees->filter(
                fn($e) =>
                str_contains(strtolower(($e->job_title ?? '') . ($e->level ?? '')), 'manager') ||
                    str_contains(strtolower(($e->job_title ?? '') . ($e->level ?? '')), 'head')
            )->count();

            $hasSpv = $employees->filter(
                fn($e) =>
                str_contains(strtolower(($e->job_title ?? '') . ($e->level ?? '')), 'supervisor') ||
                    str_contains(strtolower(($e->job_title ?? '') . ($e->level ?? '')), 'spv')
            )->count();

            $hasAdmin = $employees->filter(
                fn($e) =>
                str_contains(strtolower(($e->job_title ?? '') . ($e->level ?? '')), 'admin')
            )->count();

            if ($hasManager == 0 || $hasSpv == 0 || $hasAdmin == 0) {
                $reportData[] = [
                    'Department' => Str::limit($dept->name, 25),
                    'MGR'        => $hasManager ? '✅' : '❌',
                    'SPV'        => $hasSpv ? '✅' : '❌',
                    'ADM'        => $hasAdmin ? '✅' : '❌',
                ];
            }
        }

        if (count($reportData) > 0) {
            $this->command->table(['Department', 'MGR', 'SPV', 'ADM'], $reportData);
            $this->command->error("⚠️  Beberapa departemen kekurangan struktur Manager/SPV/Admin.");
        } else {
            $this->command->info("🎉 Struktur Organisasi Lengkap! Siap digunakan.");
        }
    }
}
