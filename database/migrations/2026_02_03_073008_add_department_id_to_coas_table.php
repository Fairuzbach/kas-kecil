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
        Schema::table('coas', function (Blueprint $table) {
            // Kita buat nullable, siapa tahu ada COA 'Global' yang bisa dipakai semua dept
            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('coas', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }
};
