<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Government;
use App\Models\OfferService;

class GovernmentOfferServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = OfferService::all()->keyBy('name');
        $governments = Government::all()->keyBy('name');

        $mappings = [
            // مستشفيات
            'خدمة الطوارئ' => ['مستشفى الثورة', 'مستشفى الجمهوري'],
            'خدمة العمليات الجراحية' => ['مستشفى الثورة'],
            'خدمة طوارئ الحرائق' => ['مستشفى الجمهوري'],

            // أقسام الشرطة
            'خدمة تقديم بلاغ' => ['قسم شرطة الصافية', 'قسم شرطة السبعين'],
            'خدمة طلب نزول عسكري' => ['قسم شرطة الصافية'],
            'خدمة ابلاغ عن مفقودات' => ['قسم شرطة السبعين'],

            // مكاتب البريد
            'خدمة صرف مرتبات الزكاة' => ['مكتب بريد شميلة'],
            'خدمة استلام طرود' => ['مكتب بريد شميلة'],
            'خدمة سداد فواتير' => ['مكتب بريد الزهراوي'],

            // الأحوال المدنية
            'خدمة استخراج شهادة وفاة' => ['الاحوال المدنية عصر'],
            'خدمة تجديد هوية' => ['الاحوال المدنية عصر', 'الاحوال المدنية شارع خولان'],
            'خدمة استخراج بدل فاقد' => ['الاحوال المدنية شارع خولان'],
        ];

        foreach ($mappings as $serviceName => $govNames) {
            $service = $services[$serviceName];
            foreach ($govNames as $govName) {
                $government = $governments[$govName];
                $government->services()->attach($service->id);
            }
        }
    }
}
