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
        Schema::create('petty_cash_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->constrained();




            $table->string('tracking_number')->unique();
            $table->string('type');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('attachment')->nullable();
            $table->string('status')->default('draft')->index();

            // Approval 1: Manager
            $table->timestamp('manager_approved_at')->nullable();
            $table->foreignId('manager_approver_id')->nullable()->constrained('users');

            // Approval 2: Director (Baru)
            $table->timestamp('director_approved_at')->nullable();
            $table->foreignId('director_approver_id')->nullable()->constrained('users');

            // Approval 3: Finance
            $table->timestamp('finance_approved_at')->nullable();
            $table->foreignId('finance_approver_id')->nullable()->constrained('users');

            $table->text('rejection_note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petty_cash_requests');
    }
};
