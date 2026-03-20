<?php

namespace Database\Seeders;

use App\Models\Government;
use App\Models\GovernmentCategory;
use Illuminate\Database\Seeder;

class GovernmentSeeder extends Seeder
{
    public function run(): void
    {
        $categories = GovernmentCategory::all()->keyBy('name');

        $governments = [
            // مستشفيات
            ['name' => 'مستشفى الثورة', 'category' => 'مستشفيات'],
            ['name' => 'مستشفى الجمهوري', 'category' => 'مستشفيات'],

            // أقسام الشرطة
            ['name' => 'قسم شرطة الصافية', 'category' => 'أقسام الشرطة'],
            ['name' => 'قسم شرطة السبعين', 'category' => 'أقسام الشرطة'],

            // مكاتب البريد
            ['name' => 'مكتب بريد شميلة', 'category' => 'مكاتب البريد'],
            ['name' => 'مكتب بريد الزهراوي', 'category' => 'مكاتب البريد'],

            // الأحوال المدنية
            ['name' => 'الاحوال المدنية عصر', 'category' => 'الأحوال المدنية'],
            ['name' => 'الاحوال المدنية شارع خولان', 'category' => 'الأحوال المدنية'],
        ];

        foreach ($governments as $gov) {
            Government::create([
                'name' => $gov['name'],
                'description' => 'وصف للجهة الحكومية',
                'government_category_id' => $categories[$gov['category']]->id,
            ]);
        }
    }
}
