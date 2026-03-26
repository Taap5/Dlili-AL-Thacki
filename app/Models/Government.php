<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Government extends Model
{
    protected $fillable = [
        'name',
        'description',
        'address',
        'phone',
        'location_lat',
        'location_long',
        'government_category_id',
        'images',
        'contact_number',
        'work_hours',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(GovernmentCategory::class, 'government_category_id');
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

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }
}
