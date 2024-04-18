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
        Schema::create('business_unit_service_center', function (Blueprint $table) {
            $table->unsignedBigInteger('business_unit_id');
            $table->unsignedBigInteger('service_center_id');
            $table->foreign('business_unit_id')->references('id')->on('business_units');
            $table->foreign('service_center_id')->references('id')->on('service_centers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_unit_service_center');
    }
};
