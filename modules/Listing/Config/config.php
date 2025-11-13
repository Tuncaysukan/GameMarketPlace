<?php

return [
    'name' => 'Listing',
    
    // Maksimum görsel sayısı
    'max_images' => 10,
    
    // Otomatik onay (false: admin onayı gerekir)
    'auto_approve_listings' => env('AUTO_APPROVE_LISTINGS', false),
    
    // Varsayılan işlem süresi (gün)
    'default_processing_days' => 3,
    
    // Boost süresi (gün)
    'boost_duration_days' => 2,
    
    // Featured süresi (gün)
    'featured_duration_days' => 30,
    
    // SEO URL yapısı
    'url_pattern' => '/ilan/{slug}-{id}',
];

