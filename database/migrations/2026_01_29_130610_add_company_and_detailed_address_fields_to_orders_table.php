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
            // Company fields
            $table->boolean('is_company')->default(false)->after('location_id');
            $table->string('company_name')->nullable()->after('is_company');
            $table->string('tax_number')->nullable()->after('company_name');
            
            // Detailed billing address fields
            $table->string('billing_postal_code', 10)->nullable()->after('address');
            $table->string('billing_city')->nullable()->after('billing_postal_code');
            $table->string('billing_street')->nullable()->after('billing_city');
            $table->string('billing_house_number', 20)->nullable()->after('billing_street');
            
            // Detailed delivery address fields
            $table->string('delivery_postal_code', 10)->nullable()->after('delivery_address');
            $table->string('delivery_city')->nullable()->after('delivery_postal_code');
            $table->string('delivery_street')->nullable()->after('delivery_city');
            $table->string('delivery_house_number', 20)->nullable()->after('delivery_street');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'is_company',
                'company_name',
                'tax_number',
                'billing_postal_code',
                'billing_city',
                'billing_street',
                'billing_house_number',
                'delivery_postal_code',
                'delivery_city',
                'delivery_street',
                'delivery_house_number'
            ]);
        });
    }
};
