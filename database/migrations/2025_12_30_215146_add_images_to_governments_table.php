<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('governments', function (Blueprint $table) {
            // إضافة حقل الصور بعد حقل الوصف، ونجعله يقبل القيمة null
            $table->json('images')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('governments', function (Blueprint $table) {
            // حذف الحقل في حال التراجع عن المهاجرة
            $table->dropColumn('images');
        });
    }
};
