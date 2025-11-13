<?php

namespace Modules\Listing\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected $moduleNamespace = 'Modules\Listing\Http\Controllers';

    public function boot()
    {
        $this->map();
        parent::boot();
    }

    public function map()
    {
        $this->mapWebRoutes();
        $this->mapAdminRoutes();
        $this->mapVendorRoutes();
    }

    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Listing', '/Routes/public.php'));
    }

    protected function mapAdminRoutes()
    {
        Route::middleware(['web', 'admin'])
            ->prefix(config('app.admin_prefix', 'admin'))
            ->as('admin.')
            ->namespace($this->moduleNamespace . '\Admin')
            ->group(module_path('Listing', '/Routes/admin.php'));
    }

    protected function mapVendorRoutes()
    {
        Route::middleware('web')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Listing', '/Routes/vendor.php'));
    }
}

