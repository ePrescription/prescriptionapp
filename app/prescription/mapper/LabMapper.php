<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/2/2016
 * Time: 7:39 PM
 */

namespace App\prescription\mapper;


use App\Http\ViewModels\LabViewModel;
use Illuminate\Http\Request;

//use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LabMapper
{
    //public static function setLabDetails(Request $labRequest)
    public static function setLabDetails($labRequest)
    {
        $labVM = new LabViewModel();
        $userName = Session::get('DisplayName');

        $labVM->setLabId(Auth::user()->id);
        $labVM->setName($labRequest['lab_name']);
        $labVM->setAddress($labRequest['address']);
        $labVM->setCity($labRequest['city']);
        $labVM->setCountry($labRequest['country']);
        $labVM->setPincode($labRequest['pincode']);
        $labVM->setTelephone($labRequest['telephone']);
        //$labVM->setEmail($labRequest['email']);
        //$labVM->setLabPhoto($labRequest['labphoto']);
        $labVM->setCreatedBy($userName);
        $labVM->setUpdatedBy($userName);
        $labVM->setCreatedAt(date("Y-m-d H:i:s"));
        $labVM->setUpdatedAt(date("Y-m-d H:i:s"));

        return $labVM;
    }
}