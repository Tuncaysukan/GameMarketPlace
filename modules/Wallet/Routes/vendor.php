<?php

use Illuminate\Support\Facades\Route;

// Vendor Wallet Routes
Route::get('wallet', [
    'as' => 'wallet.index',
    'uses' => 'WalletController@index',
]);

Route::get('wallet/transactions', [
    'as' => 'wallet.transactions',
    'uses' => 'WalletController@transactions',
]);

Route::get('wallet/withdraw', [
    'as' => 'wallet.withdraw.create',
    'uses' => 'WithdrawalController@create',
]);

Route::post('wallet/withdraw', [
    'as' => 'wallet.withdraw.store',
    'uses' => 'WithdrawalController@store',
]);

