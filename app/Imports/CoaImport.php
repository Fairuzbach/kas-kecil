<?php

namespace App\Imports;

use App\Models\Coa;
use App\Models\Department;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CoaImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // 1. BERSIHKAN DATA
            $kode = isset($row['kode_akun']) ? trim($row['kode_akun']) : '';
            $nama = isset($row['nama_akun']) ? trim($row['nama_akun']) : '';

            // Validasi: Lewati jika kode/nama kosong
            if ($kode === '' || $nama === '') {
                continue;
            }

            // 2. UPDATE ATAU BUAT BARU (Berdasarkan Kode)
            $coa = Coa::updateOrCreate(
                ['code' => $kode],
                ['name' => $nama]
            );

            // 3. LOGIC DEPARTEMEN & ALL DEPARTMENT
            $divisiRaw = isset($row['divisi']) ? strtoupper(trim($row['divisi'])) : '';

            // SKENARIO A: Jika tertulis "ALL DEPARTMENT" atau Kosong
            // Kita harus DETACH (Hapus Relasi) agar dia jadi Global (Milik Semua)
            if ($divisiRaw === 'ALL DEPARTMENT' || $divisiRaw === '') {
                $coa->departments()->detach();
                continue; // Lanjut ke baris berikutnya
            }

            // SKENARIO B: Ada nama departemen spesifik (IT, HR, dll)
            // Pecah string, ambil ID, dan SYNC
            $deptCodes = array_map('trim', explode(',', $row['divisi']));
            $deptIds = Department::whereIn('code', $deptCodes)->pluck('id');

            if ($deptIds->isNotEmpty()) {
                // sync() akan menghapus relasi lama & pasang yang baru
                $coa->departments()->sync($deptIds);
            }
        }
    }
}
