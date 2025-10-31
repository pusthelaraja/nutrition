<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contact_leads', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone', 20)->nullable();
            $table->string('subject');
            $table->text('message');
            $table->boolean('privacy_accepted')->default(true);
            $table->string('status', 32)->default('new'); // new, contacted, closed
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('page_url')->nullable();
            $table->timestamps();
            $table->index(['email', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_leads');
    }
};


