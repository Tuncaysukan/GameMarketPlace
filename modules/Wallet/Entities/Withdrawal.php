<?php

namespace Modules\Wallet\Entities;

use Modules\Support\Eloquent\Model;
use Modules\Vendor\Entities\Vendor;

class Withdrawal extends Model
{
    protected $fillable = [
        'vendor_id',
        'wallet_id',
        'amount',
        'method',
        'payment_details',
        'status',
        'note',
        'admin_note',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'payment_details' => 'array',
        'processed_at' => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}

