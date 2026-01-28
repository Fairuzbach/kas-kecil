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
        Schema::create('petty_cash_details', function (Blueprint $table) {
            $table->id();
            // Relasi ke Header
            $table->foreignId('petty_cash_request_id')->constrained()->cascadeOnDelete();

            // Relasi COA & Tipe (Pindah ke sini)
            $table->foreignId('coa_id')->constrained();


            $table->string('item_name'); // Nama Barang per baris
            $table->decimal('amount', 15, 2); // Harga per baris

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petty_cash_details');
    }
};
