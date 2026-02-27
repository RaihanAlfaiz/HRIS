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
        Schema::table('sites', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->comment('Latitude of the site for GPS attendance');
            $table->decimal('longitude', 11, 8)->nullable()->comment('Longitude of the site for GPS attendance');
            $table->integer('radius')->default(1000)->comment('Allowed check-in radius in meters');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'radius']);
        });
    }
};
