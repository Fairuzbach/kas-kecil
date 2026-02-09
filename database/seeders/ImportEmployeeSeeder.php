<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use App\Imports\EmployeeImport;

class ImportEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan path ini menunjuk ke file yang benar!
        $path = database_path('data/data_karyawan.xlsx');

        if (file_exists($path)) {
            $this->command->info('Memulai import semua karyawan ke tabel employees...');
            Excel::import(new EmployeeImport, $path);
            $this->command->info('✅ Import Employee Selesai.');
        } else {
            $this->command->error('❌ File Excel tidak ditemukan di: ' . $path);
        }
    }
}
