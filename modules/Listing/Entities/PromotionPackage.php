<?php

namespace Modules\Listing\Entities;

use Modules\Support\Money;
use Modules\Support\Eloquent\Model;

class PromotionPackage extends Model
{
    protected $fillable = [
        'name',
        'type',
        'duration_days',
        'price',
        'description',
        'position',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getPriceAttribute($price)
    {
        return Money::inDefaultCurrency($price);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('position');
    }

    public function scopeBoost($query)
    {
        return $query->where('type', 'boost');
    }

    public function scopeFeatured($query)
    {
        return $query->where('type', 'featured');
    }
}

