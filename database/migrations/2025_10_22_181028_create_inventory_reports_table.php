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
        Schema::create('inventory_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->date('report_date');
            $table->integer('opening_stock');
            $table->integer('closing_stock');
            $table->integer('stock_in');
            $table->integer('stock_out');
            $table->integer('stock_sold');
            $table->integer('stock_returned');
            $table->integer('stock_adjusted');
            $table->decimal('total_sales_amount', 10, 2)->default(0);
            $table->integer('total_orders')->default(0);
            $table->timestamps();

            // Indexes
            $table->index(['product_id', 'report_date']);
            $table->index('report_date');
            $table->unique(['product_id', 'report_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_reports');
    }
};
