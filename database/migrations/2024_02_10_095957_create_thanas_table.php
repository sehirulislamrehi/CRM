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
        Schema::create('thanas', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->unsignedBigInteger("district_id");
            $table->boolean("is_active")->default(true);
            $table->foreign('district_id')->references('id')->on('districts');
            $table->unique(['name','district_id'],'district_wise_unique_thana');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thanas');
    }
};
