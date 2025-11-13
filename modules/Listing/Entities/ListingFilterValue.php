<?php

namespace Modules\Listing\Entities;

use Modules\Support\Eloquent\Model;

class ListingFilterValue extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'listing_id',
        'filter_id',
        'filter_value',
    ];

    /**
     * Get the listing that owns the filter value.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    /**
     * Get the filter.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function filter()
    {
        return $this->belongsTo(ListingCategoryFilter::class, 'filter_id');
    }
}

