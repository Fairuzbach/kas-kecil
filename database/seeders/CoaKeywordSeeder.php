<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coa;
use Illuminate\Support\Facades\File;

class CoaKeywordSeeder extends Seeder
{
    public function run()
    {
        // Gunakan file yang sudah ada kolom help_text
        $filePath = storage_path('app/coas_with_help_text.csv');

        if (!File::exists($filePath)) {
            $this->command->error("File tidak ditemukan di: {$filePath}");
            return;
        }

        $file = fopen($filePath, "r");

        // Lewati baris pertama (Header: id, code, name, keywords, help_text)
        $header = fgetcsv($file);

        $count = 0;
        $this->command->info("Sedang melakukan mapping keywords & help text ke database...");

        while (($data = fgetcsv($file)) !== FALSE) {
            $code = trim($data[1] ?? '');
            $keywords = trim($data[3] ?? '');
            $helpText = trim($data[6] ?? '');   // Kolom ke-5 adalah help_text

            if (!empty($code)) {
                $updated = Coa::where('code', $code)->update([
                    'keywords' => $keywords,
                    'help_text' => $helpText // Update help_text juga
                ]);

                if ($updated) {
                    $count++;
                }
            }
        }

        fclose($file);

        $this->command->info("Selesai! Mapping {$count} COA beserta Help Text berhasil diupdate.");
    }
}
