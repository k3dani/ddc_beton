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
        Schema::table('product_categories', function (Blueprint $table) {
            $table->string('position_x')->nullable()->after('sort_order')->comment('X pozíció a házas képen (%-ban, pl: 48%)');
            $table->string('position_y')->nullable()->after('position_x')->comment('Y pozíció a házas képen (%-ban, pl: 7%)');
            $table->boolean('is_visible_on_house')->default(false)->after('position_y')->comment('Megjelenik-e a házas képen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn(['position_x', 'position_y', 'is_visible_on_house']);
        });
    }
};
