<?php namespace App\prescription\repositories\repointerface;
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/28/2016
 * Time: 12:26 PM
 */

namespace App\prescription\repositories\repointerface;


use App\Http\ViewModels\PharmacyViewModel;

interface PharmacyInterface
{
    public function getProfile($pharmacyId);
    public function getPatientListForPharmacy($pharmacyId, $hospitalId);
    public function getPrescriptionListForPharmacy($pharmacyId, $hospitalId);
    public function getPrescriptionByPrid($prId);
    public function editPharmacy(PharmacyViewModel $pharmacyVM);

    public function getPrescriptionListForPatient($patientId);

}