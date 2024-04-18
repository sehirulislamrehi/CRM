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
        Schema::create('service_center_user', function (Blueprint $table) {
            $table->unsignedBigInteger('service_center_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('service_center_id')->references('id')->on('service_centers');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_center_user');
    }
};
