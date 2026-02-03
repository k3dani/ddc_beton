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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('pump_id')->nullable()->after('delivery_price');
            $table->string('pump_type')->nullable()->after('pump_id');
            $table->integer('pump_fixed_fee')->nullable()->after('pump_type');
            $table->integer('pump_hourly_fee')->nullable()->after('pump_fixed_fee');
            
            $table->foreign('pump_id')->references('id')->on('pumps')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['pump_id']);
            $table->dropColumn(['pump_id', 'pump_type', 'pump_fixed_fee', 'pump_hourly_fee']);
        });
    }
};
