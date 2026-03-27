<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferService extends Model
{
    protected $fillable = [
        'name',
        'description',
        'government_category_id',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function governments()
    {
        return $this->belongsToMany(Government::class, 'government_offer_service')
            ->withPivot('description', 'contact_number', 'work_hours', 'price')
            ->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(GovernmentCategory::class, 'government_category_id');
    }
}
