<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreignId('shift_id')->nullable()->after('employee_id')->constrained('work_shifts')->nullOnDelete();
            $table->time('schedule_in')->nullable()->after('date');
            $table->time('schedule_out')->nullable()->after('schedule_in');
            $table->integer('late_minutes')->default(0)->after('check_out');
            $table->integer('early_leave_minutes')->default(0)->after('late_minutes');
            $table->decimal('work_hours_decimal', 5, 2)->default(0)->after('early_leave_minutes');
            $table->integer('overtime_minutes')->default(0)->after('work_hours_decimal');
            $table->enum('overtime_status', ['none', 'pending', 'approved', 'rejected'])->default('none')->after('overtime_minutes');
            $table->foreignId('overtime_approved_by')->nullable()->after('overtime_status')->constrained('users')->nullOnDelete();
            $table->string('check_in_photo')->nullable()->after('notes');
            $table->string('check_out_photo')->nullable()->after('check_in_photo');
            $table->string('check_in_ip', 45)->nullable()->after('check_out_photo');
            $table->string('check_out_ip', 45)->nullable()->after('check_in_ip');
            $table->decimal('lat_in', 10, 7)->nullable()->after('check_out_ip');
            $table->decimal('lng_in', 10, 7)->nullable()->after('lat_in');
            $table->decimal('lat_out', 10, 7)->nullable()->after('lng_in');
            $table->decimal('lng_out', 10, 7)->nullable()->after('lat_out');
        });

        // Add default_shift_id to employees
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('default_shift_id')->nullable()->after('join_date')->constrained('work_shifts')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['shift_id']);
            $table->dropForeign(['overtime_approved_by']);
            $table->dropColumn([
                'shift_id', 'schedule_in', 'schedule_out',
                'late_minutes', 'early_leave_minutes', 'work_hours_decimal',
                'overtime_minutes', 'overtime_status', 'overtime_approved_by',
                'check_in_photo', 'check_out_photo',
                'check_in_ip', 'check_out_ip',
                'lat_in', 'lng_in', 'lat_out', 'lng_out',
            ]);
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['default_shift_id']);
            $table->dropColumn('default_shift_id');
        });
    }
};
