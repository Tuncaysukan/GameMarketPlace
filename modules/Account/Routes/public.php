<?php

use Illuminate\Support\Facades\Route;

Route::post('callback', 'AccountDashboardController@callback')->name('account.dashboard.callback')->withoutMiddleware(\Veles\Http\Middleware\VerifyCsrfToken::class);

Route::middleware('auth')->group(function () {
    Route::get('account', 'AccountDashboardController@index')->name('account.dashboard.index');


    Route::get('account/balance', 'AccountDashboardController@balance')->name('account.dashboard.balance');
    Route::post('account/balance', 'AccountDashboardController@balance_post')->name('account.profile.balance');

    Route::get('account/money-check', 'MoneyCheckController@index')->name('account.money-check');
    Route::post('account/money-check', 'MoneyCheckController@store')->name('account.money-check.store');

    Route::get('account/reference', 'ReferenceController@index')->name('account.reference');

    Route::get('account/profile', 'AccountProfileController@edit')->name('account.profile.edit');
    Route::put('account/profile', 'AccountProfileController@update')->name('account.profile.update');

    Route::get('account/orders', 'AccountOrdersController@index')->name('account.orders.index');
    Route::get('account/orders/{id}', 'AccountOrdersController@show')->name('account.orders.show');

    Route::get('account/downloads', 'AccountDownloadsController@index')->name('account.downloads.index');
    Route::get('account/downloads/{id}', 'AccountDownloadsController@show')->name('account.downloads.show');

    Route::get('account/wishlist', 'AccountWishlistController@index')->name('account.wishlist.index');

    Route::get('account/wishlist/products', 'AccountWishlistProductController@index')->name('account.wishlist.products.index');
    Route::post('account/wishlist/products', 'AccountWishlistProductController@store')->name('account.wishlist.products.store');
    Route::delete('account/wishlist/products/{product}', 'AccountWishlistProductController@destroy')->name('account.wishlist.products.destroy');

    Route::get('account/reviews', 'AccountReviewController@index')->name('account.reviews.index');

    Route::get('account/addresses', 'AccountAddressController@index')->name('account.addresses.index');
    Route::post('account/addresses', 'AccountAddressController@store')->name('account.addresses.store');
    Route::put('account/addresses/{id}', 'AccountAddressController@update')->name('account.addresses.update');
    Route::delete('account/addresses/{id}', 'AccountAddressController@destroy')->name('account.addresses.destroy');
    Route::post('account/addresses/change-default', 'AccountAddressController@changeDefault')->name('account.addresses.change_default');
});


