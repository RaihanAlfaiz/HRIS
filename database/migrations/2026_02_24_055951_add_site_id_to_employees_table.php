<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'site_id')) {
                $table->foreignId('site_id')->nullable()->after('department_id')
                      ->constrained('sites')->cascadeOnUpdate()->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'site_id')) {
                $table->dropForeign(['site_id']);
                $table->dropColumn('site_id');
            }
        });
    }
};
