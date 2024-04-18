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
        Schema::create('product_category_problems', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->unsignedBigInteger('product_category_id');
            $table->boolean('is_active');
            $table->foreign('product_category_id')->references('id')->on('product_categories')->onDelete('restrict');
            $table->unique(['product_category_id','name'], 'category_wise_unique_problem_name');
            $table->timestamps();

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_category_problems');
    }
};
