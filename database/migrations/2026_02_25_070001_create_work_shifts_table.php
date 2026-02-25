<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');                       // "Shift Pagi"
            $table->string('code', 10)->unique();         // "SP"
            $table->time('start_time');                    // 08:00
            $table->time('end_time');                      // 17:00
            $table->time('break_start')->nullable();       // 12:00
            $table->time('break_end')->nullable();         // 13:00
            $table->integer('late_tolerance')->default(15);          // menit toleransi telat
            $table->integer('early_leave_tolerance')->default(0);    // menit boleh pulang awal
            $table->integer('minimum_work_minutes')->default(480);   // 8 jam = 480 menit
            $table->integer('overtime_threshold_minutes')->default(30); // minimal lembur dihitung (30 menit)
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default shifts
        DB::table('work_shifts')->insert([
            [
                'name' => 'Shift Pagi',
                'code' => 'SP',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'break_start' => '12:00:00',
                'break_end' => '13:00:00',
                'late_tolerance' => 15,
                'early_leave_tolerance' => 0,
                'minimum_work_minutes' => 480,
                'overtime_threshold_minutes' => 30,
                'is_default' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Shift Siang',
                'code' => 'SS',
                'start_time' => '14:00:00',
                'end_time' => '22:00:00',
                'break_start' => '18:00:00',
                'break_end' => '18:30:00',
                'late_tolerance' => 15,
                'early_leave_tolerance' => 0,
                'minimum_work_minutes' => 450,
                'overtime_threshold_minutes' => 30,
                'is_default' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Shift Malam',
                'code' => 'SM',
                'start_time' => '22:00:00',
                'end_time' => '06:00:00',
                'break_start' => '01:00:00',
                'break_end' => '01:30:00',
                'late_tolerance' => 15,
                'early_leave_tolerance' => 0,
                'minimum_work_minutes' => 450,
                'overtime_threshold_minutes' => 30,
                'is_default' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('work_shifts');
    }
};
