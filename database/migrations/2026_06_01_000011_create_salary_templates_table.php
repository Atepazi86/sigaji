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
        Schema::create('salary_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('position_id');
            $table->string('template_name')->unique();
            $table->text('description')->nullable();
            
            // ===== KOMPONEN GAJI (Earnings) =====
            $table->decimal('base_salary', 15, 2);
            $table->decimal('transport_allowance', 15, 2)->default(0);
            $table->decimal('meal_allowance', 15, 2)->default(0);
            $table->decimal('housing_allowance', 15, 2)->default(0);
            $table->decimal('communication_allowance', 15, 2)->default(0);
            $table->decimal('other_allowance', 15, 2)->default(0);
            
            // ===== KOMPONEN POTONGAN TETAP =====
            $table->decimal('bpjs_health_rate', 5, 2)->default(4);
            $table->decimal('bpjs_employment_rate', 5, 2)->default(2);
            $table->decimal('pph21_rate', 5, 2)->default(5);
            $table->boolean('include_employer_bpjs')->default(true);
            
            // ===== CUTI & KETIDAKHADIRAN =====
            $table->integer('annual_leave_days')->default(12);
            $table->integer('sick_leave_days')->default(12);
            $table->integer('special_leave_days')->default(3);
            $table->integer('maternity_leave_days')->default(90);
            
            // ===== ATURAN POTONGAN KETIDAKHADIRAN =====
            $table->enum('absence_deduction_type', ['percentage', 'fixed_amount', 'per_day_salary'])->default('per_day_salary');
            $table->decimal('alpha_deduction_value', 15, 2)->default(0);
            $table->decimal('allowed_tardiness_minutes', 5, 2)->default(10);
            $table->decimal('tardiness_deduction_value', 15, 2)->default(0);
            
            // ===== HITUNG HARI KERJA =====
            $table->integer('working_days_per_month')->default(22);
            $table->boolean('count_saturday_as_working_day')->default(false);
            $table->boolean('count_sunday_as_working_day')->default(false);
            
            // ===== STATUS & HISTORY =====
            $table->boolean('is_active')->default(true);
            $table->timestamp('effective_from')->nullable();
            $table->timestamp('effective_to')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_templates');
    }
};