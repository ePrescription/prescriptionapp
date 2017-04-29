<?php namespace App\prescription\repositories\repointerface;
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/8/2016
 * Time: 5:07 PM
 */

use App\Http\ViewModels\NewAppointmentViewModel;
use App\Http\ViewModels\PatientLabTestViewModel;
use App\Http\ViewModels\PatientPrescriptionViewModel;
use App\Http\ViewModels\PatientProfileViewModel;

interface HospitalInterface {
    public function getHospitals();
    public function getHospitalByKeyword($keyword = null);
    public function getHospitalId($userTypeId, $userId);
    public function getDoctorsByHospitalId($hospitalId);

    public function getDoctorDetails($doctorId);

    //Get Appointment details
    public function getAppointmentsByHospitalAndDoctor($hospitalId, $doctorId);
    public function saveNewAppointment(NewAppointmentViewModel $appointmentVM);

    //Get Patient List
    public function getPatientsByHospital($hospitalId, $keyword = null);
    //public function getPatientsByHospital($hospitalId);
    public function getPatientDetailsById($patientId);
    public function getPatientProfile($patientId);
    public function getPatientAppointments($patientId);

    //Get Prescription List
    public function getPrescriptions($hospitalId, $doctorId);
    public function getPrescriptionByPatient($patientId);
    public function getPrescriptionDetails($prescriptionId);
    public function savePatientPrescription(PatientPrescriptionViewModel $patientPrescriptionVM);
    public function savePatientProfile(PatientProfileViewModel $patientProfileVM);

    public function checkIsNewPatient($hospitalId, $doctorId, $patientId);

    //Search Patient
    public function searchPatientByName($keyword);
    public function searchPatientByPid($pid);

    public function searchByPatientByPidOrName($keyWord = null);

    //Drug list
    public function getTradeNames($keyword);
    public function getFormulationNames($keyword);

    //Lab Tests
    public function getLabTests();
    public function getLabTestsForPatient($hospitalId, $doctorId);
    public function getLabTestsByPatient($patientId);
    public function getLabTestDetails($labTestId);
    public function savePatientLabTests(PatientLabTestViewModel $patientLabTestVM);

    //Hospital
    public function getProfile($hospitalId);
    public function getDoctorNames($hospitalId, $keyword);
    public function getPatientNames($hospitalId, $keyword);

    //Fee receipt
    public function getFeeReceipts($hospitalId, $doctorId);
    public function getReceiptDetails($receiptId);
}