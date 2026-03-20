<?php

namespace Database\Seeders;

use App\Models\OfferService;
use Illuminate\Database\Seeder;

class OfferServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            'خدمة الطوارئ',
            'خدمة العمليات الجراحية',
            'خدمة طوارئ الحرائق',
            'خدمة تقديم بلاغ',
            'خدمة طلب نزول عسكري',
            'خدمة ابلاغ عن مفقودات',
            'خدمة صرف مرتبات الزكاة',
            'خدمة استلام طرود',
            'خدمة سداد فواتير',
            'خدمة استخراج شهادة وفاة',
            'خدمة تجديد هوية',
            'خدمة استخراج بدل فاقد',
        ];

        foreach ($services as $srv) {
            OfferService::create([
                'name' => $srv,
                'description' => 'وصف للخدمة',
            ]);
        }
    }
}
