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
        Schema::table('eligible_students', function (Blueprint $table) {
           
            $table->dropColumn('payment_status');

            
            $table->string('nhif_status')->nullable()->after('amount_paid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eligible_students', function (Blueprint $table) {
            
            $table->dropColumn('nhif_status');

            
            $table->string('payment_status')->nullable()->after('amount_paid');
        });
    }
};
