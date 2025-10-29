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
        Schema::create('payment_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('razorpay_refund_id')->unique();
            $table->string('razorpay_payment_id');
            $table->decimal('amount', 10, 2); // Refund amount
            $table->string('currency', 3)->default('INR');
            $table->enum('status', ['pending', 'processed', 'failed'])->default('pending');
            $table->text('reason')->nullable(); // Refund reason
            $table->text('notes')->nullable(); // Internal notes
            $table->json('razorpay_response')->nullable(); // Razorpay API response
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'status']);
            $table->index(['razorpay_payment_id']);
            $table->index(['created_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_refunds');
    }
};
