<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('governments', function (Blueprint $table) {
            if (!Schema::hasColumn('governments', 'work_hours_json')) {
                $table->json('work_hours_json')->nullable()->after('work_hours');
            }
        });
    }

    public function down(): void
    {
        Schema::table('governments', function (Blueprint $table) {
            if (Schema::hasColumn('governments', 'work_hours_json')) {
                $table->dropColumn('work_hours_json');
            }
        });
    }
};
