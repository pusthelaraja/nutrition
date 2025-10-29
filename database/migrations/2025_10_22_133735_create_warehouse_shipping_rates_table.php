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
        Schema::create('warehouse_shipping_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->foreignId('shipping_zone_id')->constrained()->onDelete('cascade');
            $table->foreignId('shipping_method_id')->constrained()->onDelete('cascade');
            $table->decimal('base_rate', 10, 2); // Base shipping cost from this warehouse
            $table->decimal('rate_per_kg', 10, 2)->nullable(); // Additional cost per kg
            $table->decimal('free_shipping_threshold', 10, 2)->nullable(); // Free shipping above this amount
            $table->integer('estimated_days'); // Delivery time in days
            $table->decimal('distance_km', 8, 2)->nullable(); // Distance from warehouse to zone
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['warehouse_id', 'shipping_zone_id', 'shipping_method_id'], 'wh_zone_method_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_shipping_rates');
    }
};
