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
            // Tambahkan kolom untuk menyimpan angka asli hasil scan mesin
            $table->decimal('amount_ocr', 15, 2)->nullable()->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('petty_cash_details', function (Blueprint $table) {
            $table->dropColumn('amount_ocr');
        });
    }
};
