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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., "SAVE20", "WELCOME10"
            $table->string('name'); // e.g., "20% Off Summer Sale"
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed_amount', 'free_shipping']); // Discount type
            $table->decimal('value', 10, 2); // Discount value (percentage or amount)
            $table->decimal('minimum_amount', 10, 2)->nullable(); // Minimum order amount
            $table->decimal('maximum_discount', 10, 2)->nullable(); // Max discount cap
            $table->integer('usage_limit')->nullable(); // Total usage limit
            $table->integer('usage_limit_per_user')->default(1); // Per user limit
            $table->integer('used_count')->default(0); // Current usage count
            $table->timestamp('starts_at')->nullable(); // Start date
            $table->timestamp('expires_at')->nullable(); // Expiry date
            $table->boolean('is_active')->default(true);
            $table->json('applicable_products')->nullable(); // Specific products
            $table->json('applicable_categories')->nullable(); // Specific categories
            $table->json('applicable_users')->nullable(); // Specific users
            $table->boolean('stackable')->default(false); // Can combine with other coupons
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
