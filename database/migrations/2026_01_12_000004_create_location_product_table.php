<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('location_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->string('currency')->default('HUF');
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->unique(['location_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('location_product');
    }
};
