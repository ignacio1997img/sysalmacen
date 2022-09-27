<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use TCG\Voyager\Voyager;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Pagination\Paginator;

use App\FormFields\DireccionAdministrativaFormField;

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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
