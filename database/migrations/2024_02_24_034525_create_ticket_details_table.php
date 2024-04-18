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
        Schema::create('ticket_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('business_unit_id');
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('product_category_id');
            $table->unsignedBigInteger('service_center_id');
            $table->longText('notes')->nullable();
            $table->string('comment')->nullable();
            $table->enum('status',['pending','on-process','done','cancel']);
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('restrict');
            $table->foreign('business_unit_id')->references('id')->on('business_units')->onDelete('restrict');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('restrict');
            $table->foreign('product_category_id')->references('id')->on('product_categories')->onDelete('restrict');
            $table->foreign('service_center_id')->references('id')->on('service_centers')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_details');
    }
};
