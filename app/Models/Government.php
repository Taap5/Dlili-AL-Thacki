<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Government extends Model
{
    protected $fillable = [
        'name',
        'description',
        'short_description',
        'address',
        'location_description',
        'location_lat',
        'location_long',
        'government_category_id',
        'images',
        'contact_number',
        'email',
        'whatsapp_number',
        'work_hours',
        'work_hours_json',
        'facebook_url',
        'telegram_url',
        'keywords',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'work_hours_json' => 'array',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(GovernmentCategory::class, 'government_category_id');
    }

    public function services()
    {
        return $this->belongsToMany(OfferService::class, 'government_offer_service')
            ->withPivot(
                'id',
                'description',
                'contact_number',
                'work_hours',

                'price',
                'processing_time',
                'office_location',
                'required_documents',
                'steps',
                'conditions',
                'notes',
                'requires_appointment',
                'appointment_phone',
                'doctor_specialist',
                'hospital_stay_duration',
                'emergency_notes',
                'extra_data',
                'created_at',
                'updated_at'
            )
            ->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function offers()
    {
        return $this->hasMany(GovernmentOffer::class);
    }

    public function activeOffers()
    {
        return $this->hasMany(GovernmentOffer::class)->where('is_active', true);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }

    /**
     * جلب ساعات العمل بشكل منظم
     */
    public function getWorkingHours()
    {
        if ($this->work_hours_json) {
            return $this->work_hours_json;
        }

        // محاولة تحويل النص القديم إلى JSON (مرحلة انتقالية)
        return $this->convertLegacyWorkHours();
    }

    /**
     * تحويل النص القديم إلى JSON
     */
    private function convertLegacyWorkHours()
    {
        if (!$this->work_hours) {
            return ['is_24h' => false];
        }

        // التحقق من وجود كلمات 24 ساعة
        $open24Keywords = ['24', 'مدار الساعة', 'مدار الساعه', 'على مدار', 'طوال اليوم'];
        foreach ($open24Keywords as $keyword) {
            if (str_contains($this->work_hours, $keyword)) {
                return ['is_24h' => true];
            }
        }

        // محاولة استخراج النمط: "السبت - الأربعاء: 8ص - 2م"
        if (preg_match('/([\p{Arabic}\s\-]+):\s*(\d{1,2}[صم])\s*-\s*(\d{1,2}[صم])/u', $this->work_hours, $matches)) {
            $daysPart = $matches[1];
            $startTime = $this->convertTimeTo24($matches[2]);
            $endTime = $this->convertTimeTo24($matches[3]);

            $result = ['is_24h' => false];
            $days = $this->parseDaysRange($daysPart);

            foreach ($days as $day) {
                $result[$day] = ['open' => $startTime, 'close' => $endTime];
            }

            return $result;
        }

        return ['is_24h' => false, 'original' => $this->work_hours];
    }

    /**
     * تحويل الوقت من صيغة 12 ساعة إلى 24 ساعة
     */
    private function convertTimeTo24($time)
    {
        $time = trim($time);
        $isPM = str_contains($time, 'م') && !str_contains($time, 'ص');
        $hour = (int) preg_replace('/[^0-9]/', '', $time);

        if ($isPM && $hour < 12) {
            $hour += 12;
        }
        if (!$isPM && $hour == 12) {
            $hour = 0;
        }

        return sprintf('%02d:00', $hour);
    }

    /**
     * تحويل نطاق الأيام إلى مصفوفة
     */
    private function parseDaysRange($daysString)
    {
        $daysMap = [
            'السبت' => 'saturday',
            'الأحد' => 'sunday',
            'الاثنين' => 'monday',
            'الثلاثاء' => 'tuesday',
            'الأربعاء' => 'wednesday',
            'الخميس' => 'thursday',
            'الجمعة' => 'friday',
        ];

        $result = [];
        $daysString = trim($daysString);

        if (strpos($daysString, '-') !== false) {
            $parts = explode('-', $daysString);
            $startDay = trim($parts[0]);
            $endDay = trim($parts[1]);

            $allDays = array_keys($daysMap);
            $startIndex = array_search($startDay, $allDays);
            $endIndex = array_search($endDay, $allDays);

            if ($startIndex !== false && $endIndex !== false) {
                for ($i = $startIndex; $i <= $endIndex; $i++) {
                    $result[] = $daysMap[$allDays[$i]];
                }
            }
        } else {
            $dayName = trim($daysString);
            if (isset($daysMap[$dayName])) {
                $result[] = $daysMap[$dayName];
            }
        }

        return $result;
    }

    /**
     * التحقق إذا كانت الجهة مفتوحة الآن
     */
/**
 * التحقق إذا كانت الجهة مفتوحة الآن
 */
public function isOpen()
{
    $hours = $this->getWorkingHours();

    // 24 ساعة
    if (isset($hours['is_24h']) && $hours['is_24h'] === true) {
        return true;
    }

    $now = now();
    $currentDayEn = strtolower($now->format('l')); // saturday, sunday, etc.
    $currentTime = $now->format('H:i');

    // تحويل الأيام الإنجليزية إلى العربية (للتأكد)
    $daysMap = [
        'saturday' => 'saturday',
        'sunday' => 'sunday',
        'monday' => 'monday',
        'tuesday' => 'tuesday',
        'wednesday' => 'wednesday',
        'thursday' => 'thursday',
        'friday' => 'friday',
    ];

    $dayKey = $daysMap[$currentDayEn] ?? $currentDayEn;

    if (isset($hours[$dayKey])) {
        $dayHours = $hours[$dayKey];

        // مغلق في هذا اليوم
        if (empty($dayHours['open']) || empty($dayHours['close'])) {
            return false;
        }

        // التحقق من الوقت
        return ($currentTime >= $dayHours['open'] && $currentTime <= $dayHours['close']);
    }

    return null;
}
    /**
     * الحصول على نص منسق لساعات العمل للعرض
     */
    public function getFormattedWorkHours()
    {
        $hours = $this->getWorkingHours();

        if (isset($hours['is_24h']) && $hours['is_24h'] === true) {
            return 'مفتوح 24 ساعة';
        }

        $daysMap = [
            'saturday' => 'السبت',
            'sunday' => 'الأحد',
            'monday' => 'الاثنين',
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الأربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة',
        ];

        $formatted = [];
        foreach ($daysMap as $key => $name) {
            if (isset($hours[$key]) && !empty($hours[$key]['open']) && !empty($hours[$key]['close'])) {
                $formatted[] = $name . ': ' . $hours[$key]['open'] . ' - ' . $hours[$key]['close'];
            } elseif (isset($hours[$key]) && (empty($hours[$key]['open']) || empty($hours[$key]['close']))) {
                $formatted[] = $name . ': مغلق';
            }
        }

        return implode(' | ', $formatted);
    }
}
