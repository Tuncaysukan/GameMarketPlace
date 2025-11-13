<?php

use Illuminate\Support\Facades\Route;

// Wallet Management
Route::get('wallets', [
    'as' => 'admin.wallets.index',
    'uses' => 'WalletController@index',
]);

// Withdrawals
Route::get('withdrawals', [
    'as' => 'admin.withdrawals.index',
    'uses' => 'WithdrawalController@index',
]);

Route::get('withdrawals/pending', [
    'as' => 'admin.withdrawals.pending',
    'uses' => 'WithdrawalController@pending',
]);

Route::post('withdrawals/{id}/approve', [
    'as' => 'admin.withdrawals.approve',
    'uses' => 'WithdrawalController@approve',
]);

Route::post('withdrawals/{id}/reject', [
    'as' => 'admin.withdrawals.reject',
    'uses' => 'WithdrawalController@reject',
]);

