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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('dtdc_awb_number')->nullable();
            $table->string('dtdc_consignment_number')->nullable();
            $table->enum('dtdc_status', ['booked', 'picked_up', 'in_transit', 'out_for_delivery', 'delivered', 'returned'])->nullable();
            $table->timestamp('dtdc_pickup_date')->nullable();
            $table->timestamp('dtdc_delivery_date')->nullable();
            $table->json('dtdc_tracking_details')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'dtdc_awb_number',
                'dtdc_consignment_number',
                'dtdc_status',
                'dtdc_pickup_date',
                'dtdc_delivery_date',
                'dtdc_tracking_details'
            ]);
        });
    }
};
