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
        Schema::create('business_unit_user', function (Blueprint $table) {
            $table->unsignedBigInteger('business_unit_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('business_unit_id')->references('id')->on('business_units');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_unit_user');
    }
};
