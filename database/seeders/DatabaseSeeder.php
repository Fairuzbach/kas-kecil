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
        $deptIT = Department::create(['code' => '5IT', 'name' => 'INFORMATION TECHNOLOGY']);
        $deptFin = Department::create(['code' => '5FA', 'name' => 'FINANCE & ACCOUNTING']);


        // Hubungkan COA ke Departemen (Many-to-Many)
        // IT boleh pakai: Transport & ATK


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
