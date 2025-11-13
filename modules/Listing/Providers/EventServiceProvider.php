<?php

namespace Modules\Listing\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'Modules\Listing\Events\ListingCreated' => [
            'Modules\Listing\Listeners\NotifyAdminNewListing',
        ],
        'Modules\Listing\Events\ListingApproved' => [
            'Modules\Listing\Listeners\NotifyVendorListingApproved',
        ],
        'Modules\Listing\Events\ListingRejected' => [
            'Modules\Listing\Listeners\NotifyVendorListingRejected',
        ],
        'Modules\Listing\Events\ListingViewed' => [
            'Modules\Listing\Listeners\RecordListingView',
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}

