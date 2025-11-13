<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'DashboardController@index')->name('admin.dashboard.index');

Route::get('/money-check', 'MoneyCheckController@index')->name('admin.money-check');
Route::get('/money-check/status/{id}', 'MoneyCheckController@status')->name('admin.money-check.status');

Route::get('/sales-analytics', [
    'as' => 'admin.sales_analytics.index',
    'uses' => 'SalesAnalyticsController@index',
    'middleware' => 'can:admin.orders.index',
]);

Route::get('clear-cache', function(){
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
});