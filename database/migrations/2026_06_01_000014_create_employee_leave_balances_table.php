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
        Schema::create('employee_leave_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->year('year');
            
            $table->integer('annual_leave_balance')->default(12);
            $table->integer('sick_leave_balance')->default(12);
            $table->integer('special_leave_balance')->default(3);
            $table->integer('maternity_leave_balance')->default(90);
            
            $table->integer('annual_leave_used')->default(0);
            $table->integer('sick_leave_used')->default(0);
            $table->integer('special_leave_used')->default(0);
            $table->integer('maternity_leave_used')->default(0);
            
            $table->timestamps();
            
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->unique(['employee_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_leave_balances');
    }
};