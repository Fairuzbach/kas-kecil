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
            // Tambahkan kolom help_text bertipe text dan boleh kosong (nullable)
            $table->text('help_text')->nullable()->after('keywords');
        });
    }

    public function down()
    {
        Schema::table('coas', function (Blueprint $table) {
            $table->dropColumn('help_text');
        });
    }
};
