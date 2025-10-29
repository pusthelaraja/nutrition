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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_name')->nullable(); // e.g., 'default', 'auth', 'order', 'payment'
            $table->text('description'); // Human readable description
            $table->string('subject_type')->nullable(); // Model class name
            $table->unsignedBigInteger('subject_id')->nullable(); // Model ID
            $table->string('causer_type')->nullable(); // User model class
            $table->unsignedBigInteger('causer_id')->nullable(); // User ID
            $table->json('properties')->nullable(); // Additional data
            $table->string('event')->nullable(); // e.g., 'created', 'updated', 'deleted'
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->string('method')->nullable(); // HTTP method
            $table->enum('log_level', ['info', 'warning', 'error', 'debug', 'critical'])->default('info');
            $table->timestamps();

            $table->index(['log_name', 'created_at']);
            $table->index(['subject_type', 'subject_id']);
            $table->index(['causer_type', 'causer_id']);
            $table->index(['event', 'created_at']);
            $table->index(['log_level', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
