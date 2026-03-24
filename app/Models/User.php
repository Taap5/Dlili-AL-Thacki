<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
/**
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Government[] $favoriteGovernments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OfferService[] $favoriteServices
 * @method bool hasRole($role)
 * @method bool update(array $attributes = [], array $options = [])
 * @method bool save(array $options = [])
 */
class User extends Authenticatable
{
    use Notifiable, HasRoles, LogsActivity;

    protected $fillable = [
        'user_name',
        'email',
        'phone',
        'password',
        'location_lat',
        'location_long',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // سجل التعديلات
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->logOnlyDirty()
            ->useLogName('user');
    }

    // ===== علاقات المفضلة =====

    // الجهات المفضلة (جدول favorites الحالي)
    public function favoriteGovernments()
    {
        return $this->belongsToMany(Government::class, 'favorites', 'user_id', 'government_id')
            ->withTimestamps();
    }

    // الخدمات المفضلة (جدول favorite_services الجديد)
    public function favoriteServices()
    {
        return $this->belongsToMany(OfferService::class, 'favorite_services', 'user_id', 'service_id')
            ->withTimestamps();
    }

    // التحقق إذا كانت جهة مفضلة
    public function isGovernmentFavorite($governmentId)
    {
        return $this->favoriteGovernments()->where('government_id', $governmentId)->exists();
    }

    // التحقق إذا كانت خدمة مفضلة
    public function isServiceFavorite($serviceId)
    {
        return $this->favoriteServices()->where('service_id', $serviceId)->exists();
    }

    // ===== علاقات المشروع الأخرى =====

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
