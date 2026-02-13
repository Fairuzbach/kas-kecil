<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class UpdateDirectorGroupSeeder extends Seeder
{
    public function run()
    {
        // 1. Manufacturing Director
        $manufacturing = ['FIBER OPTIC MANAGER', 'MAINTENANCE MANAGER', 'PROCESS ENGINEERING MANAGER', 'PRODUCTION PLANNING MANAGER', 'LOW VOLTAGE MANAGER', 'MEDIUM VOLTAGE MANAGER', 'FO', 'PP', 'PE', 'LV', 'MV'];
        Department::whereIn('name', $manufacturing)->orWhereIn('code', $manufacturing)
            ->update(['director_group' => 'manufacturing']);

        // 2. Finance Director
        $finance = ['FINANCE & ACCOUNTING MANAGER', 'INFORMATION TECHNOLOGY MANAGER', 'IT MANAGER', 'FA MANAGER', 'Finance'];
        Department::whereIn('name', $finance)->orWhereIn('code', $finance)
            ->update(['director_group' => 'finance']);

        // 3. President Director
        $president = ['QUALITY ASSURANCE & R D MANAGER', 'FACILITY MANAGER', 'QA MANAGER'];
        Department::whereIn('name', $president)->orWhereIn('code', $president)
            ->update(['director_group' => 'president']);

        // 4. Human Capital Director
        $hc = ['HUMAN CAPITAL MANAGER', 'GENERAL AFFAIR MANAGER', 'LEGAL DEPARTMENT', 'RESEARCH & DEVELOPMENT DEPARTMENT'];
        Department::whereIn('name', $hc)->orWhereIn('code', $hc)
            ->update(['director_group' => 'commercial']);

        // 5. Commercial & Supply Chain Director
        $commercial = ['SALES MANAGER', 'SALES 2 MANAGER', 'SALES SUPPORT MANAGER', 'MARKETING MANAGER', 'PROCUREMENT MANAGER'];
        Department::whereIn('name', $commercial)->orWhereIn('code', $commercial)
            ->update(['director_group' => 'commercial']);
    }
}
