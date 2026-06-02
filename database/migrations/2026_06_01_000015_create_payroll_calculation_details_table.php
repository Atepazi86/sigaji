<?php

namespace Database\Migrations;

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
        Schema::create('payroll_calculation_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payroll_transaction_id');
            
            $table->integer('total_working_days');
            $table->integer('present_days');
            $table->integer('absent_days');
            $table->integer('sick_days');
            $table->integer('leave_days');
            $table->integer('holiday_days');
            $table->integer('late_days');
            
            $table->text('calculation_notes')->nullable();
            $table->decimal('gaji_per_hari', 15, 2);
            $table->decimal('absence_deduction', 15, 2)->default(0);
            $table->decimal('tardiness_deduction', 15, 2)->default(0);
            
            $table->unsignedBigInteger('salary_template_id');
            
            $table->timestamps();
            
            $table->foreign('payroll_transaction_id')->references('id')->on('payroll_transactions')->onDelete('cascade');
            $table->foreign('salary_template_id')->references('id')->on('salary_templates')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_calculation_details');
    }
};