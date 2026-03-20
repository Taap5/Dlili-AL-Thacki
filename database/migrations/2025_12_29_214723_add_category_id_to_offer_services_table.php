<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('offer_services', function (Blueprint $table) {
            // إضافة عمود التصنيف، يمكن أن يكون فارغًا في البداية
            $table->foreignId('government_category_id')
                ->nullable()
                ->constrained('government_categories')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('offer_services', function (Blueprint $table) {
            $table->dropForeign(['government_category_id']);
            $table->dropColumn('government_category_id');
        });
    }
};
