<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CoaImport;

class ImportCoaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Pastikan file excel sudah ditaruh di folder storage/app/
        Excel::import(new CoaImport, storage_path('app/COA.xlsx'));

        $this->command->info('Berhasil import data COA dari Excel!');
    }
}
