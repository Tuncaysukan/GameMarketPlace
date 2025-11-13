<?php

namespace Modules\Wallet\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected $moduleNamespace = 'Modules\Wallet\Http\Controllers';

    public function boot()
    {
        $this->map();
        parent::boot();
    }

    public function map()
    {
        $this->mapAdminRoutes();
        $this->mapVendorRoutes();
    }

    protected function mapAdminRoutes()
    {
        Route::middleware(['web', 'admin'])
            ->prefix(config('app.admin_prefix', 'admin'))
            ->as('admin.')
            ->namespace($this->moduleNamespace . '\Admin')
            ->group(module_path('Wallet', '/Routes/admin.php'));
    }

    protected function mapVendorRoutes()
    {
        Route::middleware(['web', 'auth', 'vendor'])
            ->prefix('vendor')
            ->as('vendor.')
            ->namespace($this->moduleNamespace . '\Vendor')
            ->group(module_path('Wallet', '/Routes/vendor.php'));
    }
}

