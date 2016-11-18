<?php

namespace App\prescription\serviceprovider\reposerviceprovider;

use Illuminate\Support\ServiceProvider;

class HospitalRepoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\prescription\repositories\repointerface\HospitalInterface',
            'App\prescription\repositories\repoimpl\HospitalImpl');
        $this->app->bind('App\prescription\repositories\repointerface\PharmacyInterface',
            'App\prescription\repositories\repoimpl\PharmacyImpl');
        $this->app->bind('App\prescription\repositories\repointerface\LabInterface',
            'App\prescription\repositories\repoimpl\LabImpl');
        $this->app->bind('App\prescription\repositories\repointerface\HelperInterface',
            'App\prescription\repositories\repoimpl\HelperImpl');
    }
}
