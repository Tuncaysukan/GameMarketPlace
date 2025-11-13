<?php

use Illuminate\Support\Facades\Route;

Route::get('checkout', 'CheckoutController@create')->name('checkout.create');
Route::post('checkout', 'CheckoutController@store')->name('checkout.store');

Route::any('checkout/{orderId}/complete', 'CheckoutCompleteController@store')
    ->name('checkout.complete.store')
    ->withoutMiddleware(\Veles\Http\Middleware\VerifyCsrfToken::class);
Route::get('checkout/complete', 'CheckoutCompleteController@show')->name('checkout.complete.show');

Route::get('checkout/{orderId}/payment-canceled', 'PaymentCanceledController@store')->name('checkout.payment_canceled.store');

Route::post('payment/pay2out/callback', function() {
    $gateway = new \Modules\Payment\Gateways\Pay2out();
    return $gateway->callback(request()->all());
})->name('payment.pay2out.callback')
->withoutMiddleware(\Veles\Http\Middleware\VerifyCsrfToken::class);