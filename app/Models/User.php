<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

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

    // علاقات المشروع
    public function favorites()
    {
        return $this->belongsToMany(Government::class, 'favorites');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
