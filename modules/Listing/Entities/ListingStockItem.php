<?php

namespace Modules\Listing\Entities;

use Modules\Support\Eloquent\Model;
use Modules\Order\Entities\Order;

class ListingStockItem extends Model
{
    const STATUS_AVAILABLE = 'available';
    const STATUS_SOLD = 'sold';
    const STATUS_RESERVED = 'reserved';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'listing_id',
        'stock_data',
        'status',
        'order_id',
        'sold_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'sold_at' => 'datetime',
    ];

    /**
     * Get the listing that owns the stock item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    /**
     * Get the order that purchased this stock item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Check if stock item is available.
     *
     * @return bool
     */
    public function isAvailable()
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    /**
     * Check if stock item is sold.
     *
     * @return bool
     */
    public function isSold()
    {
        return $this->status === self::STATUS_SOLD;
    }

    /**
     * Check if stock item is reserved.
     *
     * @return bool
     */
    public function isReserved()
    {
        return $this->status === self::STATUS_RESERVED;
    }

    /**
     * Mark stock item as sold.
     *
     * @param int $orderId
     * @return void
     */
    public function markAsSold($orderId)
    {
        $this->update([
            'status' => self::STATUS_SOLD,
            'order_id' => $orderId,
            'sold_at' => now(),
        ]);
    }

    /**
     * Reserve stock item.
     *
     * @return void
     */
    public function reserve()
    {
        $this->update(['status' => self::STATUS_RESERVED]);
    }

    /**
     * Release reserved stock item.
     *
     * @return void
     */
    public function release()
    {
        $this->update(['status' => self::STATUS_AVAILABLE]);
    }

    /**
     * Scope a query to only include available stock items.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    /**
     * Scope a query to only include sold stock items.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSold($query)
    {
        return $query->where('status', self::STATUS_SOLD);
    }
}

