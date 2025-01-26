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
        Schema::create('skydrop_trackings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');  // Cambiado a unsignedBigInteger
            $table->string('tracking_number')->nullable();
            $table->string('carrier_name')->nullable();
            $table->unsignedBigInteger('order_id');  // Cambiado a unsignedBigInteger
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skydrop_trackings');
    }
};
