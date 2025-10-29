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
        Schema::create('payment_webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('webhook_id')->unique(); // Razorpay webhook ID
            $table->string('event_type'); // e.g., 'payment.captured', 'payment.failed'
            $table->string('entity_type'); // e.g., 'payment', 'order'
            $table->string('entity_id'); // Razorpay entity ID
            $table->json('payload'); // Complete webhook payload
            $table->string('signature'); // Razorpay signature
            $table->enum('status', ['received', 'processed', 'failed', 'ignored'])->default('received');
            $table->text('processing_notes')->nullable();
            $table->integer('retry_count')->default(0);
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamps();

            $table->index(['event_type', 'status']);
            $table->index(['entity_id', 'entity_type']);
            $table->index(['created_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_webhooks');
    }
};
