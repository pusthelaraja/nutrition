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
        Schema::create('razorpay_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('razorpay_order_id')->nullable();
            $table->string('razorpay_payment_id')->nullable();
            $table->string('event_type'); // e.g., 'order.created', 'payment.captured', 'payment.failed'
            $table->enum('log_level', ['info', 'warning', 'error', 'debug'])->default('info');
            $table->string('action'); // e.g., 'create_order', 'capture_payment', 'refund_payment'
            $table->enum('status', ['success', 'failed', 'pending'])->default('pending');
            $table->text('message')->nullable(); // Human readable message
            $table->json('request_data')->nullable(); // Request sent to Razorpay
            $table->json('response_data')->nullable(); // Response from Razorpay
            $table->json('error_details')->nullable(); // Error information
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('currency', 3)->default('INR');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'event_type']);
            $table->index(['razorpay_order_id', 'status']);
            $table->index(['created_at', 'log_level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('razorpay_logs');
    }
};
