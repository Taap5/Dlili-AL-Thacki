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
       Schema::create('users', function (Blueprint $table) {
           $table->id();
           $table->string('user_name', 100);
           $table->string('email')->unique();
           $table->string('phone', 20)->nullable();
           $table->string('password');
           $table->decimal('location_lat', 10, 8)->nullable();
           $table->decimal('location_long', 11, 8)->nullable();
           $table->rememberToken();
           $table->timestamps();
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
