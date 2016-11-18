<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/8/2016
 * Time: 5:42 PM
 */

namespace App\prescription\facades;
use \Illuminate\Support\Facades\Facade;

class HospitalServiceFacade extends Facade {

    protected static function getFacadeAccessor() {
        //dd('Inside facade');
        return 'HospitalService';
    }
}