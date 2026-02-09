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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('name');
            $table->string('job_title')->nullable(); // Jabatan (Kolom D)
            $table->string('level')->nullable();     // Level (Kolom E)
            $table->string('organization')->nullable(); // Org Asli (Kolom C)
            $table->string('branch')->nullable();    // Branch (Kolom G)
            $table->string('status')->nullable();    // Status (Kolom F)

            // Relasi ke Struktur
            $table->foreignId('department_id')->nullable()->constrained();
            $table->foreignId('division_id')->nullable()->constrained();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
