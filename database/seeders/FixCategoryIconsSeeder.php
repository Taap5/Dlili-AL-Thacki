<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GovernmentCategory;

class FixCategoryIconsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🔧 تصحيح أسماء التصنيفات وإضافة الأيقونات...');

        // خريطة الأسماء الصحيحة مع أيقوناتها
        $categoriesMap = [
            'مستشفيات' => [
                'icon' => 'fas fa-hospital',
                'alt_names' => ['المستشفيات', 'مستشفى', 'صحة', 'طبية']
            ],
            'أقسام الشرطة' => [
                'icon' => 'fas fa-shield-alt',
                'alt_names' => ['اقسام الشرطة', 'شرطة', 'أمن', 'الأمن']
            ],
            'الأحوال المدنية' => [
                'icon' => 'fas fa-id-card',
                'alt_names' => ['الاحوال المدنية', 'أحوال مدنية', 'هوية', 'بطاقة']
            ],
            'مكاتب البريد' => [
                'icon' => 'fas fa-mail-bulk',
                'alt_names' => ['بريد', 'مكتب بريد', 'بريدية']
            ]
        ];

        $updated = 0;

        foreach ($categoriesMap as $correctName => $data) {
            // البحث بالتساوي
            $category = GovernmentCategory::where('name', $correctName)->first();

            if (!$category) {
                // البحث بالبدائل
                foreach ($data['alt_names'] as $altName) {
                    $category = GovernmentCategory::where('name', 'like', "%{$altName}%")->first();
                    if ($category) break;
                }
            }

            if ($category) {
                $oldName = $category->name;
                $oldIcon = $category->icon;

                // تصحيح الاسم إذا كان مختلفاً
                if ($oldName !== $correctName) {
                    $category->name = $correctName;
                    $this->command->warn("✏️ تم تصحيح الاسم: '{$oldName}' → '{$correctName}'");
                }

                // تحديث الأيقونة
                if ($oldIcon !== $data['icon']) {
                    $category->icon = $data['icon'];
                    $this->command->info("✅ تم تحديث أيقونة: '{$correctName}' → {$data['icon']}");
                } else {
                    $this->command->line("ℹ️  '{$correctName}' → الأيقونة موجودة مسبقاً");
                }

                $category->save();
                $updated++;
            } else {
                $this->command->error("❌ لم يتم العثور على: {$correctName}");
            }
        }

        $this->command->info("\n📊 تم تحديث {$updated} من 4 تصنيفات");

        // عرض النتيجة النهائية
        $this->displayResult();
    }

    private function displayResult(): void
    {
        $this->command->line("\n📋 التصنيفات بعد التحديث:");
        $this->command->line("==========================");

        $categories = GovernmentCategory::orderBy('name')->get(['name', 'icon']);

        foreach ($categories as $cat) {
            $iconStatus = $cat->icon
                ? "✅ <i class='{$cat->icon}'></i> {$cat->icon}"
                : "❌ بدون أيقونة";

            $this->command->line("• {$cat->name}: {$iconStatus}");
        }
    }
}
