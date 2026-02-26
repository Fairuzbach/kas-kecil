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
        Schema::table('petty_cash_requests', function (Blueprint $table) {
            // Kolom untuk menyimpan ID user yang menolak (bisa null)
            $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null');

            // Kolom untuk alasan penolakan (bisa null)
            $table->text('rejection_note')->nullable();
        });
    }

    public function down()
    {
        Schema::table('petty_cash_requests', function (Blueprint $table) {
            $table->dropForeign(['rejected_by']);
            $table->dropColumn(['rejected_by', 'rejection_note']);
        });
    }
};
