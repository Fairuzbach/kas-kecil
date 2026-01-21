<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use App\Models\Coa;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Departemen
        $deptIT = Department::create(['code' => 'IT', 'name' => 'Information Technology']);
        $deptFin = Department::create(['code' => 'FIN', 'name' => 'Finance & Accounting']);

        // 2. Buat COA (Chart of Accounts)
        $coa1 = Coa::create(['code' => '6001', 'name' => 'Biaya Transportasi']);
        $coa2 = Coa::create(['code' => '6002', 'name' => 'Biaya Konsumsi']);
        $coa3 = Coa::create(['code' => '6003', 'name' => 'Pembelian ATK']);

        // Hubungkan COA ke Departemen (Many-to-Many)
        // IT boleh pakai: Transport & ATK
        $deptIT->coas()->attach([$coa1->id, $coa3->id]);
        // Finance boleh pakai semua
        $deptFin->coas()->attach([$coa1->id, $coa2->id, $coa3->id]);

        // 3. Buat User untuk Simulasi Approval

        // User A: Staff IT (Requester)
        // ... code departments & coa sebelumnya ...

        // User A: Staff IT
        User::create([
            'nik' => '1001', // <--- Tambah ini
            'name' => 'Ahmad Staff',
            'email' => 'staff@app.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
            'department_id' => $deptIT->id,
        ]);

        // User B: Manager IT
        User::create([
            'nik' => '2001', // <--- Tambah ini
            'name' => 'Budi Manager',
            'email' => 'manager@app.com',
            'password' => bcrypt('password'),
            'role' => 'manager',
            'department_id' => $deptIT->id,
        ]);

        // User C: Director IT
        User::create([
            'nik' => '3001', // <--- Tambah ini
            'name' => 'Charlie Director',
            'email' => 'director@app.com',
            'password' => bcrypt('password'),
            'role' => 'director',
            'department_id' => $deptIT->id,
        ]);

        // User D: Finance
        User::create([
            'nik' => '4001', // <--- Tambah ini
            'name' => 'Dina Finance',
            'email' => 'finance@app.com',
            'password' => bcrypt('password'),
            'role' => 'finance',
            'department_id' => $deptFin->id,
        ]);
    }
}
