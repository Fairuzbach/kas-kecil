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

            // Cek apakah kolom rejected_by BELUM ada, jika belum, baru buat.
            if (!Schema::hasColumn('petty_cash_requests', 'rejected_by')) {
                $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null');
            }

            // Cek apakah kolom rejection_note BELUM ada
            if (!Schema::hasColumn('petty_cash_requests', 'rejection_note')) {
                $table->text('rejection_note')->nullable();
            }
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
