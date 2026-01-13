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
        Schema::table('location_product', function (Blueprint $table) {
            // Töröljük a régi price és currency mezőket
            $table->dropColumn(['price', 'currency']);
            
            // Hozzáadjuk az új mezőket
            $table->decimal('gross_price', 10, 2)->nullable()->after('product_id');
            $table->decimal('net_price', 10, 2)->nullable()->after('gross_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('location_product', function (Blueprint $table) {
            // Visszaállítjuk a régi mezőket
            $table->dropColumn(['gross_price', 'net_price']);
            $table->decimal('price', 10, 2)->after('product_id');
            $table->string('currency')->default('HUF')->after('price');
        });
    }
};
