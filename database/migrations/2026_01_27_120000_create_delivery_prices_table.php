<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->decimal('distance_from_km', 10, 2);
            $table->decimal('distance_to_km', 10, 2);
            $table->decimal('price_per_cbm', 10, 2);
            $table->timestamps();
            
            $table->index(['location_id', 'distance_from_km', 'distance_to_km']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_prices');
    }
};
