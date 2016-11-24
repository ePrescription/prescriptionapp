<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/2/2016
 * Time: 3:33 PM
 */

namespace App\prescription\mapper;

use App\Http\ViewModels\PharmacyViewModel;
use Illuminate\Http\Request;

//use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HospitalMapper
{
    //public static function setPhamarcyDetails(Request $pharmacyRequest)
    public static function setPhamarcyDetails($pharmacyRequest)
    {
        $pharmacyVM = new PharmacyViewModel();
        $userName = Session::get('DisplayName');
        $pharmacyVM->setPharmacyId(Auth::user()->id);
        $pharmacyVM->setName($pharmacyRequest['pharmacy_name']);
        $pharmacyVM->setAddress($pharmacyRequest['address']);
        $pharmacyVM->setCity($pharmacyRequest['city']);
        $pharmacyVM->setCountry($pharmacyRequest['country']);
        $pharmacyVM->setTelephone($pharmacyRequest['telephone']);
        //$pharmacyVM->setEmail($pharmacyRequest['email']);
        $pharmacyVM->setCreatedBy($userName);
        $pharmacyVM->setUpdatedBy($userName);
        $pharmacyVM->setCreatedAt(date("Y-m-d H:i:s"));
        $pharmacyVM->setUpdatedAt(date("Y-m-d H:i:s"));
        //$pharmacyPhone

        return $pharmacyVM;
    }
}