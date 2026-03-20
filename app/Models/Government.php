<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Government extends Model
{
    protected $fillable = [
        'name',
        'description',
        'phone',
        'location_lat',
        'location_long',
        'category_id',
        'images', // 1. أضفنا الحقل هنا ليسمح لنا بحفظه
    ];

    // 2. أضفنا هذا الجزء لتحويل النص الجاي من القاعدة إلى مصفوفة صور تلقائياً
    protected $casts = [
        'images' => 'array',
    ];

    public function category()
    {
        // ملاحظة جانبية: تأكد أن المفتاح الخارجي هو category_id وليس id
        return $this->belongsTo(GovernmentCategory::class, 'category_id');
    }

    public function services()
    {
        return $this->belongsToMany(OfferService::class, 'government_offer_service');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
}
