<?php

namespace Modules\Listing\Entities;

use Modules\Support\Eloquent\Model;
use Modules\User\Entities\User;
use Modules\Order\Entities\Order;

class ListingReview extends Model
{
    protected $fillable = [
        'listing_id',
        'user_id',
        'order_id',
        'reviewer_name',
        'rating',
        'comment',
        'vendor_reply',
        'vendor_replied_at',
        'is_approved',
        'is_verified_purchase',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_verified_purchase' => 'boolean',
        'vendor_replied_at' => 'datetime',
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeVerifiedPurchase($query)
    {
        return $query->where('is_verified_purchase', true);
    }
}

