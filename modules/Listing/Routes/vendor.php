<?php

use Illuminate\Support\Facades\Route;

// Account Vendor Listing Routes (Account içinde vendor yönetimi)
Route::middleware(['web', 'auth'])->prefix('account/vendor')->name('account.vendor.')->group(function () {
    
    // Listings Management (Vendor kontrolü controller'da yapılıyor)
    Route::get('listings', 'Vendor\ListingController@index')->name('listings.index');
    Route::get('listings/create', 'Vendor\ListingController@create')->name('listings.create');
    Route::post('listings', 'Vendor\ListingController@store')->name('listings.store');
    Route::get('listings/{id}/edit', 'Vendor\ListingController@edit')->name('listings.edit');
    Route::put('listings/{id}', 'Vendor\ListingController@update')->name('listings.update');
    Route::delete('listings/{id}', 'Vendor\ListingController@destroy')->name('listings.destroy');
    Route::post('listings/{id}/submit', 'Vendor\ListingController@submitForApproval')->name('listings.submit');
});

