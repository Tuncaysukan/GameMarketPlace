<?php

return [
    // Admin Listing Management
    'admin.listings' => [
        'index' => 'listing::permissions.admin.listings.index',
        'show' => 'listing::permissions.admin.listings.show',
        'approve' => 'listing::permissions.admin.listings.approve',
        'reject' => 'listing::permissions.admin.listings.reject',
        'destroy' => 'listing::permissions.admin.listings.destroy',
    ],

    // Admin Category Filters
    'admin.category_filters' => [
        'index' => 'listing::permissions.admin.filters.index',
        'create' => 'listing::permissions.admin.filters.create',
        'edit' => 'listing::permissions.admin.filters.edit',
        'destroy' => 'listing::permissions.admin.filters.destroy',
    ],
];

