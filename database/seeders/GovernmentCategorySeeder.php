<?php

namespace Database\Seeders;
use App\Models\GovernmentCategory;
use Illuminate\Database\Seeder;

class GovernmentCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'مستشفيات', 'description' => 'جهات طبية وصحية'],
            ['name' => 'أقسام الشرطة', 'description' => 'جهات أمنية'],
            ['name' => 'مكاتب البريد', 'description' => 'خدمات بريدية'],
            ['name' => 'الأحوال المدنية', 'description' => 'خدمات الهوية والأحوال الشخصية'],
        ];

        foreach ($categories as $category) {
            GovernmentCategory::create($category);
        }
    }
}
