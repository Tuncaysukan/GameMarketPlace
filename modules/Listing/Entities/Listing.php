<?php

namespace Modules\Listing\Entities;

use Modules\Support\Money;
use Modules\Support\Eloquent\Model;
use Modules\Support\Eloquent\Sluggable;
use Modules\Media\Entities\File;
use Modules\Vendor\Entities\Vendor;
use Modules\Category\Entities\Category;
use Illuminate\Database\Eloquent\SoftDeletes;

class Listing extends Model
{
    use Sluggable, SoftDeletes;

    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_INACTIVE = 'inactive';

    const DELIVERY_AUTOMATIC = 'automatic';
    const DELIVERY_MANUAL = 'manual';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vendor_id',
        'category_id',
        'title',
        'slug',
        'description',
        'price',
        'sku',
        'delivery_type',
        'manage_stock',
        'stock_qty',
        'in_stock',
        'manual_delivery_note',
        'processing_days',
        'status',
        'is_active',
        'rejection_reason',
        'approved_by',
        'approved_at',
        'is_featured',
        'is_boosted',
        'boost_expires_at',
        'featured_expires_at',
        'view_count',
        'order_count',
        'total_sales',
        'rating',
        'rating_count',
        'meta_description',
        'meta_keywords',
        'custom_fields',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'manage_stock' => 'boolean',
        'in_stock' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_boosted' => 'boolean',
        'approved_at' => 'datetime',
        'boost_expires_at' => 'datetime',
        'featured_expires_at' => 'datetime',
        'deleted_at' => 'datetime',
        'custom_fields' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'primary_image',
        'is_available',
    ];

    /**
     * Get the sluggable configuration for the model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'separator' => '-',
                'unique' => true,
                'uniqueSuffix' => function ($slug, $separator, $model) {
                    // Aynı slug'dan kaç tane var?
                    $count = static::where('slug', 'like', $slug . '%')
                        ->where('id', '!=', $model->id)
                        ->count();
                    
                    // Eğer varsa, sonuna sayı ekle
                    return $count > 0 ? $separator . ($count + 1) : '';
                },
            ]
        ];
    }

    /**
     * Get the listing's vendor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get the listing's category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the listing's images.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(ListingImage::class)->orderBy('position');
    }

    /**
     * Get the listing's stock items.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stockItems()
    {
        return $this->hasMany(ListingStockItem::class);
    }

    /**
     * Get available stock items.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function availableStockItems()
    {
        return $this->stockItems()->where('status', 'available');
    }

    /**
     * Get the listing's filter values.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function filterValues()
    {
        return $this->hasMany(ListingFilterValue::class);
    }

    /**
     * Get the listing's views.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function views()
    {
        return $this->hasMany(ListingView::class);
    }

    /**
     * Get the listing's reviews.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(ListingReview::class);
    }

    /**
     * Get approved reviews.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function approvedReviews()
    {
        return $this->reviews()->approved();
    }

    /**
     * Get the listing's promotions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function promotions()
    {
        return $this->hasMany(ListingPromotion::class);
    }

    /**
     * Get the primary image.
     *
     * @return File|null
     */
    public function getPrimaryImageAttribute()
    {
        // Eğer images ilişkisi yüklendiyse, collection'dan al
        if ($this->relationLoaded('images')) {
            $primaryImage = $this->images->where('is_primary', true)->first();
            
            if ($primaryImage && $primaryImage->file) {
                return $primaryImage->file;
            }

            $firstImage = $this->images->first();
            return $firstImage && $firstImage->file ? $firstImage->file : null;
        }

        // İlişki yüklenmemişse sorgu yap
        $primaryImage = $this->images()->where('is_primary', true)->first();
        
        if ($primaryImage) {
            return File::find($primaryImage->file_id);
        }

        $firstImage = $this->images()->first();
        
        return $firstImage ? File::find($firstImage->file_id) : null;
    }

    /**
     * Get the price as Money object.
     *
     * @param string $price
     * @return Money
     */
    public function getPriceAttribute($price)
    {
        return Money::inDefaultCurrency($price);
    }

    /**
     * Get the total sales as Money object.
     *
     * @param string $totalSales
     * @return Money
     */
    public function getTotalSalesAttribute($totalSales)
    {
        return Money::inDefaultCurrency($totalSales);
    }

    /**
     * Check if listing is available for purchase.
     *
     * @return bool
     */
    public function getIsAvailableAttribute()
    {
        if (!$this->is_active || !$this->isApproved()) {
            return false;
        }

        if ($this->delivery_type === self::DELIVERY_AUTOMATIC) {
            return $this->in_stock && $this->availableStockItems()->count() > 0;
        }

        return $this->in_stock;
    }

    /**
     * Check if listing is approved.
     *
     * @return bool
     */
    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if listing is pending.
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if listing is rejected.
     *
     * @return bool
     */
    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Check if listing is draft.
     *
     * @return bool
     */
    public function isDraft()
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * Check if listing uses automatic delivery.
     *
     * @return bool
     */
    public function isAutomaticDelivery()
    {
        return $this->delivery_type === self::DELIVERY_AUTOMATIC;
    }

    /**
     * Check if listing uses manual delivery.
     *
     * @return bool
     */
    public function isManualDelivery()
    {
        return $this->delivery_type === self::DELIVERY_MANUAL;
    }

    /**
     * Check if listing is featured.
     *
     * @return bool
     */
    public function isFeatured()
    {
        if (!$this->is_featured) {
            return false;
        }

        if ($this->featured_expires_at) {
            return now()->lte($this->featured_expires_at);
        }

        return true;
    }

    /**
     * Check if listing is boosted.
     *
     * @return bool
     */
    public function isBoosted()
    {
        if (!$this->is_boosted) {
            return false;
        }

        if ($this->boost_expires_at) {
            return now()->lte($this->boost_expires_at);
        }

        return true;
    }

    /**
     * Approve the listing.
     *
     * @param int $adminId
     * @return void
     */
    public function approve($adminId)
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'is_active' => true,
            'approved_by' => $adminId,
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);
    }

    /**
     * Reject the listing.
     *
     * @param string $reason
     * @return void
     */
    public function reject($reason = null)
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'is_active' => false,
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Submit for approval.
     *
     * @return void
     */
    public function submitForApproval()
    {
        $this->update(['status' => self::STATUS_PENDING]);
    }

    /**
     * Increment view count.
     *
     * @return void
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    /**
     * Update listing statistics after order.
     *
     * @param int $qty
     * @param float $amount
     * @return void
     */
    public function updateStats($qty, $amount)
    {
        $this->increment('order_count', $qty);
        $this->increment('total_sales', $amount);

        // Stok güncelle
        if ($this->isAutomaticDelivery() && $this->manage_stock) {
            $this->decrement('stock_qty', $qty);

            if ($this->stock_qty <= 0) {
                $this->update(['in_stock' => false]);
            }
        }
    }

    /**
     * Update listing rating.
     *
     * @param float $rating
     * @return void
     */
    public function updateRating($rating)
    {
        $totalRating = ($this->rating * $this->rating_count) + $rating;
        $newRatingCount = $this->rating_count + 1;
        $newRating = $totalRating / $newRatingCount;

        $this->update([
            'rating' => round($newRating, 2),
            'rating_count' => $newRatingCount,
        ]);
    }

    /**
     * Get listing's public URL.
     *
     * @return string
     */
    public function url()
    {
        return route('listings.show', ['slug' => $this->slug, 'id' => $this->id]);
    }

    /**
     * Scope a query to only include active listings.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope a query to only include available listings.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->active()->where('in_stock', true);
    }

    /**
     * Scope a query to only include featured listings.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)
            ->where(function ($q) {
                $q->whereNull('featured_expires_at')
                    ->orWhere('featured_expires_at', '>', now());
            });
    }

    /**
     * Scope a query to only include boosted listings.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBoosted($query)
    {
        return $query->where('is_boosted', true)
            ->where(function ($q) {
                $q->whereNull('boost_expires_at')
                    ->orWhere('boost_expires_at', '>', now());
            });
    }

    /**
     * Scope a query to only include pending listings.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope a query by category.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $categoryId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope a query by vendor.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $vendorId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }
}

