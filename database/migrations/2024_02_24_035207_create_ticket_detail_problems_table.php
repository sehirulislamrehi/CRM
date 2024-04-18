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
        Schema::create('ticket_detail_problems', function (Blueprint $table) {
            $table->unsignedBigInteger('ticket_details_id');
            $table->unsignedBigInteger('product_category_problem_id');
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->unsignedBigInteger('product_category_id');
            $table->foreign('ticket_details_id')->references('id')->on('ticket_details')->onDelete('restrict');
            $table->foreign('product_category_problem_id')->references('id')->on('product_category_problems')->onDelete('restrict');
            $table->foreign('product_category_id')->references('id')->on('product_categories')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_detail_problems');
    }
};
