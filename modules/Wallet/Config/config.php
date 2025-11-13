<?php

return [
    'name' => 'Wallet',
    
    // Para birimi
    'currency' => env('WALLET_CURRENCY', 'TRY'),
    
    // Minimum çekim tutarı
    'min_withdrawal' => env('MIN_WITHDRAWAL_AMOUNT', 100),
    
    // Maksimum çekim tutarı
    'max_withdrawal' => env('MAX_WITHDRAWAL_AMOUNT', 50000),
    
    // Bekleyen bakiye süresi (gün) - Sipariş tamamlandıktan kaç gün sonra bakiye serbest bırakılacak
    'pending_release_days' => env('PENDING_RELEASE_DAYS', 7),
];

