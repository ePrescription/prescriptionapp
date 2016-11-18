<?php

namespace App\prescription\serviceprovider\servicesserviceprovider;

use Illuminate\Support\ServiceProvider;
use App\prescription\services\HospitalService;

class HospitalServiceProvider extends ServiceProvider
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
        //dd('Inside register');
        $this->app->bind('HospitalService', function($app){
            //return new HospitalService($app->make('App\Treatin\Repositories\RepoInterface\HospitalProfileInterface'));
            $hospitalService = new HospitalService($app->make('App\prescription\repositories\repointerface\HospitalInterface'));
            return $hospitalService;

        });
    }
}
