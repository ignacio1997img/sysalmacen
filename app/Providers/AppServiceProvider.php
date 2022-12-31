<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use TCG\Voyager\Voyager;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Pagination\Paginator;

use App\FormFields\DireccionAdministrativaFormField;
use App\FormFields\SucursalFormField;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Voyager::addFormField(DireccionAdministrativaFormField::class);
        Voyager::addFormField(SucursalFormField::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
    }
}
