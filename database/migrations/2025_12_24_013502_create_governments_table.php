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
       Schema::create('governments', function (Blueprint $table) {
           $table->id();
           $table->string('name', 150);
           $table->text('description')->nullable();
           $table->decimal('location_lat', 10, 8)->nullable();
           $table->decimal('location_long', 11, 8)->nullable();
           $table->string('contact_number', 20)->nullable();
           $table->string('work_hours', 50)->nullable();
           $table->foreignId('government_category_id')->constrained()->cascadeOnDelete();
           $table->timestamps();
  });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('governments');
    }
};
