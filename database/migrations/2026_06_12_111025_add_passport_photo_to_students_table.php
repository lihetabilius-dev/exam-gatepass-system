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
        Schema::table('students', function (Blueprint $table) {
           
            if (!Schema::hasColumn('students', 'passport_photo')) {
                $table->string('passport_photo')->nullable()->after('course');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            
            if (Schema::hasColumn('students', 'passport_photo')) {
                $table->dropColumn('passport_photo');
            }
        });
    }
};