<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('government_offer_service', function (Blueprint $table) {
            $table->text('description')->nullable()->after('offer_service_id');
            $table->string('contact_number')->nullable()->after('description');
            $table->string('work_hours')->nullable()->after('contact_number');
            $table->string('price')->nullable()->after('work_hours');
        });
    }

    public function down(): void
    {
        Schema::table('government_offer_service', function (Blueprint $table) {
            $table->dropColumn(['description', 'contact_number', 'work_hours', 'price']);
        });
    }
};
