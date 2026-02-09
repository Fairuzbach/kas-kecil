<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('petty_cash_details', function (Blueprint $table) {
            // Ubah kolom coa_id menjadi NULLABLE (Boleh Kosong)
            $table->foreignId('coa_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('petty_cash_details', function (Blueprint $table) {
            // Kembalikan jadi NOT NULL (Wajib Isi) kalau di-rollback
            $table->foreignId('coa_id')->nullable(false)->change();
        });
    }
};
