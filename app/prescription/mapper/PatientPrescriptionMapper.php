<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/31/2016
 * Time: 6:12 PM
 */

namespace App\prescription\mapper;

use Illuminate\Http\Request;
use App\Http\ViewModels\PatientPrescriptionViewModel;
use App\Http\ViewModels\PatientLabTestViewModel;


class PatientPrescriptionMapper {

    public static function setPrescriptionDetails(Request $prescriptionRequests)
    {
        $prescriptionVM = new PatientPrescriptionViewModel();
        $prescription = (object) $prescriptionRequests->all();

        $prescriptionVM->setDoctorId($prescription->doctorId);
        $prescriptionVM->setPatientId($prescription->patientId);
        $prescriptionVM->setHospitalId($prescription->hospitalId);
        $prescriptionVM->setBriefDescription($prescription->notes);
        //$prescriptionVM->setIntakeForm($prescription->intakeForm);
        $prescriptionVM->setDrugDetails($prescription->drugs);
        $prescriptionVM->setPrescriptionDate(date("Y-m-d H:i:s"));
        $prescriptionVM->setCreatedAt(date("Y-m-d H:i:s"));
        $prescriptionVM->setUpdatedAt(date("Y-m-d H:i:s"));

        //$prescriptionVM->setDrugDetails($prescriptionRequests->json()->all());

        /*$prescriptionVM->setDoctorId($prescriptionRequests->get('doctorId'));
        $prescriptionVM->setPatientId($prescriptionRequests->get('patientId'));
        $prescriptionVM->setHospitalId($prescriptionRequests->get('hospitalId'));
        $prescriptionVM->setDrugDetails($prescriptionRequests->get('drugs'));*/

        return $prescriptionVM;

    }

    public static function setLabTestDetails(Request $labTestRequest)
    {
        $labTestVM = new PatientLabTestViewModel();
        $labTests = (object) $labTestRequest->all();

        $labTestVM->setDoctorId($labTests->doctorId);
        $labTestVM->setPatientId($labTests->patientId);
        $labTestVM->setHospitalId($labTests->hospitalId);
        $labTestVM->setDescription($labTests->notes);
        $labTestVM->setLabTestDetails($labTests->labtests);
        //$labTestVM->setDescription($labTests->description);
        $labTestVM->setLabTestDate(date("Y-m-d H:i:s"));

        return $labTestVM;
    }
}