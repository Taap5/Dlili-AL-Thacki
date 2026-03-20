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
        Schema::create('government_offer_service', function (Blueprint $table) {
           $table->id();
           $table->foreignId('government_id')->constrained()->cascadeOnDelete();
           $table->foreignId('offer_service_id')->constrained()->cascadeOnDelete();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('government_offer_service');
    }
};
