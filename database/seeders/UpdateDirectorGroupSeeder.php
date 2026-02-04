<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class UpdateDirectorGroupSeeder extends Seeder
{
    public function run()
    {
        // 1. Manufacturing Director
        $manufacturing = ['FIBER OPTIC', 'MAINTENANCE', 'PROCESS ENGINEERING', 'PRODUCTION PLANNING', 'LOW VOLTAGE', 'MEDIUM VOLTAGE', 'FO', 'PP', 'PE', 'LV', 'MV'];
        Department::whereIn('name', $manufacturing)->orWhereIn('code', $manufacturing)
            ->update(['director_group' => 'manufacturing']);

        // 2. Finance Director
        $finance = ['FINANCE & ACCOUNTING', 'INFORMATION TECHNOLOGY', 'IT', 'FA', 'Finance'];
        Department::whereIn('name', $finance)->orWhereIn('code', $finance)
            ->update(['director_group' => 'finance']);

        // 3. President Director
        $president = ['QUALITY ASSURANCE & R D', 'FACILITY', 'QA'];
        Department::whereIn('name', $president)->orWhereIn('code', $president)
            ->update(['director_group' => 'president']);

        // 4. Human Capital Director
        $hc = ['HUMAN CAPITAL', 'GENERAL AFFAIR', 'HC', 'GA', 'HRD'];
        Department::whereIn('name', $hc)->orWhereIn('code', $hc)
            ->update(['director_group' => 'hc']);

        // 5. Commercial & Supply Chain Director
        $commercial = ['SALES', 'SALES 2', 'SALES SUPPORT', 'MARKETING', 'PROCUREMENT'];
        Department::whereIn('name', $commercial)->orWhereIn('code', $commercial)
            ->update(['director_group' => 'commercial']);
    }
}
