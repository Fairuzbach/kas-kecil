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

            $table->foreignId('approver_id')
                ->nullable()
                ->after('user_id')
                ->constrained('users');
        });
    }

    public function down()
    {
        Schema::table('petty_cash_requests', function (Blueprint $table) {
            $table->dropForeign(['approver_id']);
            $table->dropColumn('approver_id');
        });
    }
};
