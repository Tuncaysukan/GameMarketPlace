<?php

namespace Modules\Listing\Entities;

use Modules\Support\Eloquent\Model;
use Modules\Media\Entities\File;

class ListingImage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'listing_id',
        'file_id',
        'position',
        'is_primary',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Get the listing that owns the image.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    /**
     * Get the file.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function file()
    {
        return $this->belongsTo(File::class);
    }

    /**
     * Get the image path attribute.
     *
     * @return string
     */
    public function getPathAttribute()
    {
        return optional($this->file)->path ?? '';
    }
}

