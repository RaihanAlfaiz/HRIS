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
        Schema::create('employee_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->unique()->constrained('employees')->cascadeOnDelete();
            $table->string('nik_ktp', 20)->unique();
            $table->string('place_of_birth', 100);
            $table->date('date_of_birth');
            $table->string('gender', 10);
            $table->string('religion', 50);
            $table->string('marital_status', 50);
            $table->string('blood_type', 5)->nullable();
            $table->text('address_ktp');
            $table->text('address_domicile')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_profiles');
    }
};
