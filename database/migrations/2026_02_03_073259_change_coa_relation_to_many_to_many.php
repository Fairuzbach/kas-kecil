<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // 1. Cek dulu: Kalau tabel pivot BELUM ada, baru kita buat
        if (!Schema::hasTable('coa_department')) {
            Schema::create('coa_department', function (Blueprint $table) {
                $table->id();
                $table->foreignId('coa_id')->constrained('coas')->onDelete('cascade');
                $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');

                // Mencegah duplikasi
                $table->unique(['coa_id', 'department_id']);
            });
        }

        // 2. Cek dulu: Kalau kolom lama (department_id) MASIH ada, baru kita hapus
        if (Schema::hasColumn('coas', 'department_id')) {
            Schema::table('coas', function (Blueprint $table) {
                // Hapus foreign key dulu (nama constraint biasanya otomatis)
                // Gunakan try-catch atau pastikan nama constraint benar. 
                // Jika error 'Drop foreign key constraint failed', coba cek nama constraint di database.
                $table->dropForeign(['department_id']);

                // Baru hapus kolomnya
                $table->dropColumn('department_id');
            });
        }
    }

    public function down()
    {
        // Rollback: Hapus tabel pivot, kembalikan kolom lama
        Schema::dropIfExists('coa_department');

        Schema::table('coas', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->constrained('departments');
        });
    }
};
