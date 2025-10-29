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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Mumbai Warehouse", "Delhi Warehouse"
            $table->string('code')->unique(); // e.g., "MUM", "DEL"
            $table->string('address');
            $table->string('pincode', 10);
            $table->string('city');
            $table->string('state');
            $table->string('country')->default('India');
            $table->decimal('latitude', 10, 8)->nullable(); // For distance calculation
            $table->decimal('longitude', 11, 8)->nullable(); // For distance calculation
            $table->boolean('is_active')->default(true);
            $table->boolean('is_primary')->default(false); // Primary warehouse
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
