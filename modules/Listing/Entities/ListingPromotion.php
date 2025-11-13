<?php

namespace Modules\Listing\Entities;

use Modules\Support\Eloquent\Model;
use Modules\Vendor\Entities\Vendor;

class ListingPromotion extends Model
{
    protected $fillable = [
        'listing_id',
        'vendor_id',
        'type',
        'duration_days',
        'price',
        'start_date',
        'end_date',
        'status',
        'order_id',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function isActive()
    {
        return $this->status === 'active' && now()->between($this->start_date, $this->end_date);
    }

    public function isExpired()
    {
        return now()->isAfter($this->end_date);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }
}

