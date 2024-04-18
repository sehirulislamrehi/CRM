<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     */
    // id (unsigned, bigint)
    // name (varchar)
    // phone (varchar)
    // email (varchar, null)
    // staff_id (unsigned, unique)
    // password (varchar)
    // user_type_id (foreignId)
    // role_id (foreignId)
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('fullname');
            $table->string('phone')->unique();
            $table->string('phone_login')->nullable();
            $table->string('phone_password')->nullable();
            $table->string('email')->nullable();
            $table->unsignedBigInteger('user_group_id');
            $table->string('password');
            $table->unsignedBigInteger('role_id')->nullable();
            $table->boolean('is_active')->default(false);
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            $table->foreign('user_group_id')->references('id')->on('user_groups')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
