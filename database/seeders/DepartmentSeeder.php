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
            ['code' => '2FH', 'name' => 'FACILITY'],
            ['code' => '2IC', 'name' => 'INTERNAL CONTROL'],
            ['code' => '2PD', 'name' => 'PRESIDENT DIRECTOR'],
            ['code' => '2QR', 'name' => 'QUALITY ASSURANCE & R D'],
            ['code' => '2CS', 'name' => 'COMMERCIAL & SUPPLY CHAIN DIRECTOR'],
            ['code' => '3MK', 'name' => 'MARKETING'],
            ['code' => '3PC', 'name' => 'PROCUREMENT'],
            ['code' => '3SL', 'name' => 'SALES'],
            ['code' => '3SL2', 'name' => 'SALES 2'],
            ['code' => '3SS', 'name' => 'SALES SUPPORT'],
            ['code' => '4FO', 'name' => 'FIBER OPTIC'],
            ['code' => '4LV', 'name' => 'LOW VOLTAGE'],
            ['code' => '4MN', 'name' => 'MANUFACTURING DIRECTOR'],
            ['code' => '4MT', 'name' => 'MAINTENANCE'],
            ['code' => '4MV', 'name' => 'MEDIUM VOLTAGE'],
            ['code' => '4PE', 'name' => 'PROCESS ENGINEERING'],
            ['code' => '4PP', 'name' => 'PRODUCTION PLANNING'],
            ['code' => '5FA', 'name' => 'FINANCE & ACCOUNTING'],
            ['code' => '5FN', 'name' => 'FINANCE DIRECTOR'],
            ['code' => '5IT', 'name' => 'INFORMATION TECHNOLOGY'],
            ['code' => '6GA', 'name' => 'GENERAL AFFAIR'],
            ['code' => '6HC', 'name' => 'HUMAN CAPITAL'],
            ['code' => '6HG', 'name' => 'HUMAN CAPITAL DIRECTOR'],
            ['code' => '6LG', 'name' => 'LEGAL'],
            ['code' => '6RD', 'name' => 'RESEARCH & DEVELOPMENT'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(
                ['code' => $dept['code']], // Cek based on Code
                ['name' => $dept['name']]  // Data lain
            );
        }
    }
}
