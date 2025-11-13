<?php

namespace Modules\Listing\Services;

use Modules\Listing\Entities\Listing;
use Modules\Listing\Entities\ListingPromotion;
use Modules\Listing\Entities\PromotionPackage;

class PromotionService
{
    /**
     * Boost a listing.
     *
     * @param Listing $listing
     * @param int $packageId
     * @return ListingPromotion
     */
    public function boostListing(Listing $listing, $packageId)
    {
        $package = PromotionPackage::findOrFail($packageId);

        if ($package->type !== 'boost') {
            throw new \Exception('Geçersiz paket tipi.');
        }

        $startDate = now();
        $endDate = now()->addDays($package->duration_days);

        // Promotion kaydı oluştur
        $promotion = ListingPromotion::create([
            'listing_id' => $listing->id,
            'vendor_id' => $listing->vendor_id,
            'type' => 'boost',
            'duration_days' => $package->duration_days,
            'price' => $package->attributes['price'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
        ]);

        // Listing'i güncelle
        $listing->update([
            'is_boosted' => true,
            'boost_expires_at' => $endDate,
        ]);

        return $promotion;
    }

    /**
     * Make listing featured.
     *
     * @param Listing $listing
     * @param int $packageId
     * @return ListingPromotion
     */
    public function makeFeatured(Listing $listing, $packageId)
    {
        $package = PromotionPackage::findOrFail($packageId);

        if ($package->type !== 'featured') {
            throw new \Exception('Geçersiz paket tipi.');
        }

        $startDate = now();
        $endDate = now()->addDays($package->duration_days);

        // Promotion kaydı oluştur
        $promotion = ListingPromotion::create([
            'listing_id' => $listing->id,
            'vendor_id' => $listing->vendor_id,
            'type' => 'featured',
            'duration_days' => $package->duration_days,
            'price' => $package->attributes['price'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
        ]);

        // Listing'i güncelle
        $listing->update([
            'is_featured' => true,
            'featured_expires_at' => $endDate,
        ]);

        return $promotion;
    }

    /**
     * Check and expire promotions.
     *
     * @return int
     */
    public function expirePromotions()
    {
        $expiredPromotions = ListingPromotion::where('status', 'active')
            ->where('end_date', '<', now())
            ->get();

        $count = 0;

        foreach ($expiredPromotions as $promotion) {
            $promotion->update(['status' => 'expired']);

            // Listing'den kaldır
            if ($promotion->type === 'boost') {
                $promotion->listing->update(['is_boosted' => false]);
            } else {
                $promotion->listing->update(['is_featured' => false]);
            }

            $count++;
        }

        return $count;
    }

    /**
     * Get active promotion packages.
     *
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPackages($type = null)
    {
        $query = PromotionPackage::active();

        if ($type) {
            $query->where('type', $type);
        }

        return $query->get();
    }

    /**
     * Check if listing can be promoted.
     *
     * @param Listing $listing
     * @param string $type
     * @return bool
     */
    public function canPromote(Listing $listing, $type)
    {
        if (!$listing->isApproved() || !$listing->is_active) {
            return false;
        }

        if ($type === 'boost' && $listing->isBoosted()) {
            return false;
        }

        if ($type === 'featured' && $listing->isFeatured()) {
            return false;
        }

        return true;
    }
}

