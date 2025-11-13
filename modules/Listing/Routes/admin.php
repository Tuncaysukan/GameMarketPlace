<?php

use Illuminate\Support\Facades\Route;

// Listings Management
Route::get('listings', [
    'as' => 'admin.listings.index',
    'uses' => 'ListingController@index',
]);

Route::get('listings/pending', [
    'as' => 'admin.listings.pending',
    'uses' => 'ListingController@pending',
]);

Route::get('listings/{id}', [
    'as' => 'admin.listings.show',
    'uses' => 'ListingController@show',
]);

Route::post('listings/{id}/approve', [
    'as' => 'admin.listings.approve',
    'uses' => 'ListingController@approve',
]);

Route::post('listings/{id}/reject', [
    'as' => 'admin.listings.reject',
    'uses' => 'ListingController@reject',
]);

Route::post('listings/{id}/toggle-featured', [
    'as' => 'admin.listings.toggle_featured',
    'uses' => 'ListingController@toggleFeatured',
]);

Route::post('listings/{id}/toggle-active', [
    'as' => 'admin.listings.toggle_active',
    'uses' => 'ListingController@toggleActive',
]);

Route::delete('listings/{id}', [
    'as' => 'admin.listings.destroy',
    'uses' => 'ListingController@destroy',
]);

