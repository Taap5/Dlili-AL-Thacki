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
        'icon_image',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function governments()
    {
        return $this->belongsToMany(Government::class, 'government_offer_service')
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

    public function category()
    {
        return $this->belongsTo(GovernmentCategory::class, 'government_category_id');
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorite_services', 'service_id', 'user_id');
    }
}
