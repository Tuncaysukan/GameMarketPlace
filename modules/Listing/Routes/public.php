<?php

use Illuminate\Support\Facades\Route;

// Public Listing Routes
Route::get('ilanlar', [
    'as' => 'listings.index',
    'uses' => 'ListingController@index',
]);

Route::get('ilan/{slug}-{id}', [
    'as' => 'listings.show',
    'uses' => 'ListingController@show',
])->where('id', '[0-9]+');

