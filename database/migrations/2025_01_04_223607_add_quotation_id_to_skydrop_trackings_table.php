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
        Schema::table('skydrop_trackings', function (Blueprint $table) {
            $table->unsignedBigInteger('quotation_id')->nullable()->after('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skydrop_trackings', function (Blueprint $table) {
            $table->dropColumn('quotation_id');
        });
    }
};
