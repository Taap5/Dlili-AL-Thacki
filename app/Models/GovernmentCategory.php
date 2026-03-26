<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GovernmentCategory extends Model
{
    protected $fillable = ['name', 'description', 'icon'];

    protected $attributes = [
        'icon' => 'fas fa-building', // أيقونة افتراضية
    ];

    public function governments()
    {
        return $this->hasMany(Government::class, 'government_category_id'); // تم التعديل
    }
}
