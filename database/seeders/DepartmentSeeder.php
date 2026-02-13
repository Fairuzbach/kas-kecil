<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        // Daftar Departemen Baru (Pastikan 'code' SAMA PERSIS dengan Excel)
        $departments = [
            ['code' => '0DIR', 'name' => 'DIR'],
            ['code' => '1AC', 'name' => 'AUDIT COMITEE'],
            ['code' => '1CO', 'name' => 'COMMISIONER'],
            ['code' => '1NR', 'name' => 'NOMINATION & REMUNERATION'],
            ['code' => '2FH', 'name' => 'FACILITY MANAGER'],
            ['code' => '2IC', 'name' => 'INTERNAL CONTROL'],
            ['code' => '2PD', 'name' => 'PRESIDENT DIRECTOR'],
            ['code' => '2QR', 'name' => 'QUALITY ASSURANCE & R D MANAGER'],
            ['code' => '2CS', 'name' => 'COMMERCIAL & SUPPLY CHAIN DIRECTOR'],
            ['code' => '3MK', 'name' => 'MARKETING MANAGER'],
            ['code' => '3PC', 'name' => 'PROCUREMENT MANAGER'],
            ['code' => '3SL', 'name' => 'SALES MANAGER'],
            ['code' => '3SL2', 'name' => 'SALES 2 MANAGER'],
            ['code' => '3SS', 'name' => 'SALES SUPPORT MANAGER'],
            ['code' => '4FO', 'name' => 'FIBER OPTIC MANAGER'],
            ['code' => '4LV', 'name' => 'LOW VOLTAGE MANAGER'],
            ['code' => '4MN', 'name' => 'MANUFACTURING DIRECTOR'],
            ['code' => '4MT', 'name' => 'MAINTENANCE MANAGER'],
            ['code' => '4MV', 'name' => 'MEDIUM VOLTAGE MANAGER'],
            ['code' => '4PE', 'name' => 'PROCESS ENGINEERING MANAGER'],
            ['code' => '4PP', 'name' => 'PRODUCTION PLANNING MANAGER'],
            ['code' => '5FA', 'name' => 'FINANCE & ACCOUNTING MANAGER'],
            ['code' => '5FN', 'name' => 'FINANCE DIRECTOR'],
            ['code' => '5IT', 'name' => 'INFORMATION TECHNOLOGY MANAGER'],
            ['code' => '6GA', 'name' => 'GENERAL AFFAIR MANAGER'],
            ['code' => '6HC', 'name' => 'HUMAN CAPITAL MANAGER'],
            ['code' => '6HG', 'name' => 'HUMAN CAPITAL DIRECTOR'],
            ['code' => '6LG', 'name' => 'LEGAL DEPARTMENT'],
            ['code' => '6RD', 'name' => 'RESEARCH & DEVELOPMENT DEPARTMENT'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(
                ['code' => $dept['code']], // Cek based on Code
                ['name' => $dept['name']]  // Data lain
            );
        }
    }
}
