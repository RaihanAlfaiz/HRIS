<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_kpis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('period', 20); // e.g. "2024-Q1", "2024-H1", "2024"
            $table->decimal('score', 5, 2); // 0.00 – 100.00
            $table->string('rating', 20); // Excellent, Good, Average, Below Average, Poor
            $table->text('notes')->nullable();
            $table->string('reviewed_by', 150)->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_kpis');
    }
};
