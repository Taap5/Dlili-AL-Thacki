<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GovernmentOffer extends Model
{
    protected $table = 'government_offers';

    protected $fillable = [
        'government_id',
        'title',
        'description',
        'offer_type',
        'target_audience',
        'start_date',
        'end_date',
        'is_permanent',
        'terms',
        'contact_number',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_permanent' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function government()
    {
        return $this->belongsTo(Government::class);
    }

    // هل العرض فعال حالياً؟
    public function isCurrentlyActive()
    {
        if ($this->is_permanent) {
            return true;
        }

        if (!$this->is_active) {
            return false;
        }

        $today = now()->startOfDay();

        if ($this->start_date && $today < $this->start_date) {
            return false;
        }

        if ($this->end_date && $today > $this->end_date) {
            return false;
        }

        return true;
    }

    // نص مناسب للعرض (مستمر / منتهي / فعال)
    public function getStatusTextAttribute()
    {
        if ($this->is_permanent) {
            return 'مستمر';
        }

        if (!$this->isCurrentlyActive()) {
            return 'منتهي';
        }

        return 'فعال';
    }

    // لون الحالة
    public function getStatusColorAttribute()
    {
        if ($this->is_permanent) {
            return 'success';
        }

        if (!$this->isCurrentlyActive()) {
            return 'secondary';
        }

        return 'primary';
    }
}
