<?php

use Modules\Listing\Entities\Listing;

if (!function_exists('listing_url')) {
    /**
     * Generate listing URL.
     *
     * @param Listing|int $listing
     * @return string
     */
    function listing_url($listing)
    {
        if (is_numeric($listing)) {
            $listing = Listing::find($listing);
        }

        return $listing ? $listing->url() : '#';
    }
}

if (!function_exists('listing_image_url')) {
    /**
     * Get listing primary image URL.
     *
     * @param Listing $listing
     * @param string $size
     * @return string
     */
    function listing_image_url($listing, $size = 'medium')
    {
        $image = $listing->primary_image;

        if (!$image) {
            return asset('images/placeholder.png');
        }

        return $image->path;
    }
}

