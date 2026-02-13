<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('petty_cash_requests', function (Blueprint $table) {

            $table->timestamp('supervisor_approved_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('petty_cash_requests', function (Blueprint $table) {
            $table->dropColumn([
                'supervisor_approved_at',

            ]);
        });
    }
};
