<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/8/2016
 * Time: 5:12 PM
 */

namespace App\prescription\services;

use App\prescription\facades\HospitalServiceFacade;
use App\prescription\repositories\repointerface\HospitalInterface;
use App\prescription\utilities\Exception\HospitalException;
use App\prescription\utilities\ErrorEnum\ErrorEnum;
use App\prescription\utilities\Exception\UserNotFoundException;
use Illuminate\Support\Facades\DB;

use Exception;


class HospitalService {

    protected $hospitalRepo;

    public function __construct(HospitalInterface $hospitalRepo)
    {
        //dd('Inside constructor');
        $this->hospitalRepo = $hospitalRepo;
    }

    /**
     * Get list of hospitals
     * @param none
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getHospitals()
    {
        $hospitals = null;

        try
        {
            $hospitals = $this->hospitalRepo->getHospitals();
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_LIST_ERROR, $exc);
        }

        return $hospitals;
    }

    /**
     * Get doctor details
     * @param $doctorId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getDoctorDetails($doctorId)
    {
        $doctorDetails = null;

        try
        {
            $doctorDetails = $this->hospitalRepo->getDoctorDetails($doctorId);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::DOCTOR_DETAILS_ERROR, $exc);
        }

        return $doctorDetails;
    }

    /**
     * Get list of hospitals by keyword
     * @param $keyword
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getHospitalByKeyword($keyword = null)
    {
        $hospitals = null;
        //dd('Inside service method');

        try
        {
            $hospitals = $this->hospitalRepo->getHospitalByKeyword($keyword);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_LIST_ERROR, $exc);
        }

        return $hospitals;
    }

    /**
     * Get list of doctors for the hospital
     * @param $hospitalId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getDoctorsByHospitalId($hospitalId)
    {
        $doctors = null;

        try
        {
            $doctors = $this->hospitalRepo->getDoctorsByHospitalId($hospitalId);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_DOCTOR_LIST_ERROR, $exc);
        }

        return $doctors;
    }

    /**
     * Get list of hospitals for the doctor
     * @param $email
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getHospitalsForDoctor($email)
    {
        $hospitals = null;

        try
        {
            $hospitals = $this->hospitalRepo->getHospitalsForDoctor($email);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_LIST_ERROR, $exc);
        }

        return $hospitals;
    }

    /**
     * Get list of hospitals for the doctor
     * @param $doctorId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getHospitalsByDoctorId($doctorId)
    {
        $hospitals = null;

        try
        {
            $hospitals = $this->hospitalRepo->getHospitalsByDoctorId($doctorId);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_LIST_ERROR, $exc);
        }

        return $hospitals;
    }

    /**
     * Save patient profile
     * @param $patientProfileVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientProfile($patientProfileVM)
    {
        $status = true;

        try
        {
            DB::transaction(function() use ($patientProfileVM, &$status)
            {
                $status = $this->hospitalRepo->savePatientProfile($patientProfileVM);
            });

        }
        catch(HospitalException $hospitalExc)
        {
            $status = false;
            throw $hospitalExc;
        }
        catch(UserNotFoundException $userExc)
        {
            $status = false;
            throw $userExc;
        }
        catch (Exception $ex) {

            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_PROFILE_SAVE_ERROR, $ex);
        }

        return $status;
    }

    /**
     * Check if a patient is a new patient or follow up patient
     * @param $hospitalId, $doctorId, $patientId
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function checkIsNewPatient($hospitalId, $doctorId, $patientId)
    {
        $isNewPatient = true;

        try
        {
            $isNewPatient = $this->hospitalRepo->checkIsNewPatient($hospitalId, $doctorId, $patientId);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::NEW_PATIENT_ERROR, $exc);
        }

        return $isNewPatient;
    }

    //Get Appointment details

    /**
     * Get list of appointments for the hospital and the doctor
     * @param $hospitalId, $doctorId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getAppointmentsByHospitalAndDoctor($hospitalId, $doctorId)
    {
        $appointments = null;

        try
        {
            $appointments = $this->hospitalRepo->getAppointmentsByHospitalAndDoctor($hospitalId, $doctorId);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::APPOINTMENT_LIST_ERROR, $exc);
        }

        return $appointments;
    }

    //Get Patient List
    /**
     * Get list of patients for the hospital and patient name
     * @param $hospitalId, $keyword
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientsByHospital($hospitalId, $keyword)
    //public function getPatientsByHospital($hospitalId)
    {
        $patients = null;

        try
        {
            $patients = $this->hospitalRepo->getPatientsByHospital($hospitalId, $keyword);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $exc);
        }

        return $patients;
    }

    /**
     * Get list of patients for the hospital and doctor
     * @param $hospitalId, $doctorId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientsByHospitalAndDoctor($hospitalId, $doctorId)
    {
        $patients = null;

        try
        {
            $patients = $this->hospitalRepo->getPatientsByHospitalAndDoctor($hospitalId, $doctorId);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $exc);
        }

        return $patients;
    }

    /**
     * Get patient details by patient id
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientDetailsById($patientId)
    {
        $patientDetails = null;

        try
        {
            $patientDetails = $this->hospitalRepo->getPatientDetailsById($patientId);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $exc);
        }

        return $patientDetails;
    }

    //Get Patient Profile
    /**
     * Get patient profile by patient id
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientProfile($patientId)
    {
        $patientProfile = null;

        try
        {
            $patientProfile = $this->hospitalRepo->getPatientProfile($patientId);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_PROFILE_ERROR, $exc);
        }

        return $patientProfile;
    }

    //Get Prescription List
    /**
     * Get list of prescriptions for the selected hospital and doctor
     * @param $hospitalId, $doctorId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPrescriptions($hospitalId, $doctorId)
    {
        $prescriptions = null;

        try
        {
            $prescriptions = $this->hospitalRepo->getPrescriptions($hospitalId, $doctorId);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_LIST_ERROR, $exc);
        }

        return $prescriptions;
    }

    /**
     * Get list of prescriptions for the patient
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPrescriptionByPatient($patientId)
    {
        $prescriptions = null;

        try
        {
            $prescriptions = $this->hospitalRepo->getPrescriptionByPatient($patientId);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_LIST_ERROR, $exc);
        }

        return $prescriptions;
    }

    /**
     * Get patient appointments
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientAppointments($patientId)
    {
        $appointments = null;

        try
        {
            $appointments = $this->hospitalRepo->getPatientAppointments($patientId);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_APPOINTMENT_LIST_ERROR, $exc);
        }

        return $appointments;
    }

    /**
     * Get patient appointments by hospital
     * @param $patientId, $hospitalId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientAppointmentsByHospital($patientId, $hospitalId)
    {
        $appointments = null;

        try
        {
            $appointments = $this->hospitalRepo->getPatientAppointmentsByHospital($patientId, $hospitalId);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_APPOINTMENT_LIST_ERROR, $exc);
        }

        return $appointments;
    }

    /**
     * Get prescription details for the patient by prescription Id
     * @param $prescriptionId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPrescriptionDetails($prescriptionId)
    {
        $prescriptionDetails = null;

        try
        {
            $prescriptionDetails = $this->hospitalRepo->getPrescriptionDetails($prescriptionId);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_DETAILS_ERROR, $exc);
        }

        return $prescriptionDetails;
    }

    /**
     * Save Prescription for the patient
     * @param
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientPrescription($patientPrescriptionVM)
    {
        $status = true;

        try
        {
            DB::transaction(function() use ($patientPrescriptionVM, &$status)
            {
                $status = $this->hospitalRepo->savePatientPrescription($patientPrescriptionVM);
            });

        }
        catch(HospitalException $hospitalExc)
        {
            $status = false;
            throw $hospitalExc;
        }
        catch (Exception $ex) {

            $status = false;
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_DETAILS_SAVE_ERROR, $ex);
        }

        return $status;
    }

    //Search by Patient Name
    /**
     * Get patient names by keyword
     * @param $keyword
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function searchPatientByName($keyword)
    {
        $patientNames = null;

        try
        {
            $patientNames = $this->hospitalRepo->searchPatientByName($keyword);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $exc);
        }

        return $patientNames;
    }

    //Search by Patient Pid
    /**
     * Get patient details by PID
     * @param $pid
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function searchPatientByPid($pid)
    {
        $patient = null;

        try
        {
            $patient = $this->hospitalRepo->searchPatientByPid($pid);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $exc);
        }

        return $patient;
    }

    /**
     * Get patient by Pid or Name
     * @param $keyWord
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function searchByPatientByPidOrName($keyWord = null)
    {
        $patients = null;

        try
        {
            $patients = $this->hospitalRepo->searchByPatientByPidOrName($keyWord);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $exc);
        }

        return $patients;
    }

    /**
     * Save new appointments for the patient
     * @param $newAppointmentVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function saveNewAppointment($newAppointmentVM)
    {
        $status = true;

        try
        {
            DB::transaction(function() use ($newAppointmentVM, &$status)
            {
                $status = $this->hospitalRepo->saveNewAppointment($newAppointmentVM);
            });

        }
        catch(HospitalException $hospitalExc)
        {
            $status = false;
            throw $hospitalExc;
        }
        catch (Exception $ex) {

            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_NEW_APPOINTMENT_ERROR, $ex);
        }

        return $status;
    }

    //Drugs
    /**
     * Get brand names by keyword
     * @param $keyword
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getTradeNames($keyword)
    {
        $brands = null;

        try
        {
            $brands = $this->hospitalRepo->getTradeNames($keyword);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::BRAND_LIST_ERROR, $exc);
        }

        return $brands;
    }

    /**
     * Get formulation names by keyword
     * @param $keyword
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getFormulationNames($keyword)
    {
        $formulations = null;

        try
        {
            $formulations = $this->hospitalRepo->getFormulationNames($keyword);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::FORMULATION_LIST_ERROR, $exc);
        }

        return $formulations;
    }

    //Lab Tests
    /**
     * Get all lab tests
     * @param $keyword
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getLabTests($keyword)
    {
        $labTests = null;

        try
        {
            $labTests = $this->hospitalRepo->getLabTests($keyword);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::LAB_LIST_ERROR, $exc);
        }

        return $labTests;
    }

    /**
     * Get list of lab tests for the selected hospital and doctor
     * @param $hospitalId, $doctorId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getLabTestsForPatient($hospitalId, $doctorId)
    {
        $patientLabTests = null;

        try
        {
            $patientLabTests = $this->hospitalRepo->getLabTestsForPatient($hospitalId, $doctorId);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::LAB_LIST_ERROR, $exc);
        }

        return $patientLabTests;
    }

    /**
     * Get list of labtests for the patient
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getLabTestsByPatient($patientId)
    {
        $patientLabTests = null;

        try
        {
            $patientLabTests = $this->hospitalRepo->getLabTestsByPatient($patientId);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::LAB_LIST_ERROR, $exc);
        }

        return $patientLabTests;
    }

    /**
     * Get lab test details for the given lab test id
     * @param $labTestId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getLabTestDetails($labTestId)
    {
        $labTestDetails = null;

        try
        {
            $labTestDetails = $this->hospitalRepo->getLabTestDetails($labTestId);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::LAB_DETAILS_ERROR, $exc);
        }

        return $labTestDetails;
    }

    /**
     * Save labtests for the patient
     * @param $patientLabTestVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientLabTests($patientLabTestVM)
    {
        $status = true;

        try
        {
            DB::transaction(function() use ($patientLabTestVM, &$status)
            {
                $status = $this->hospitalRepo->savePatientLabTests($patientLabTestVM);
            });

        }
        catch(HospitalException $hospitalExc)
        {
            $status = false;
            throw $hospitalExc;
        }
        catch (Exception $ex) {

            $status = false;
            throw new HospitalException(null, ErrorEnum::PRESCRIPTION_DETAILS_SAVE_ERROR, $ex);
        }

        return $status;
    }

    /**
     * Get the hospital id for the given pharmacy or lab id
     * @param $userTypeId, $userId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getHospitalId($userTypeId, $userId)
    {
        $hospitalId = null;

        try
        {
            $hospitalId = $this->hospitalRepo->getHospitalId($userTypeId, $userId);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_ID_ERROR, $exc);
        }

        return $hospitalId;
    }


    public function getProfile($hospitalId)
    {
        $hospitalProfile = null;

        try
        {
            $hospitalProfile = $this->hospitalRepo->getProfile($hospitalId);
        }
        catch(HospitalException $profileExc)
        {
            throw $profileExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_PROFILE_VIEW_ERROR, $exc);
        }

        return $hospitalProfile;
    }

    /**
     * Get the doctor names for the hospital
     * @param $hospitalId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getDoctorNames($hospitalId, $keyword)
    {
        $doctors = null;

        try
        {
            $doctors = $this->hospitalRepo->getDoctorNames($hospitalId, $keyword);
        }
        catch(HospitalException $profileExc)
        {
            throw $profileExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::HOSPITAL_NO_DOCTORS_FOUND, $exc);
        }

        return $doctors;
    }

    /**
     * Get patient names by keyword
     * @param $keyword
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientNames($hospitalId, $keyword)
    {
        $patientNames = null;

        try
        {
            $patientNames = $this->hospitalRepo->getPatientNames($hospitalId, $keyword);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_LIST_ERROR, $exc);
        }

        return $patientNames;
    }

    //Fee Receipts

    /**
     * Get list of fee receipts for the hospital and doctor
     * @param $hospitalId, $doctorId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getFeeReceipts($hospitalId, $doctorId)
    {
        $feeReceipts = null;

        try
        {
            $feeReceipts = $this->hospitalRepo->getFeeReceipts($hospitalId, $doctorId);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(UserNotFoundException $userExc)
        {
            //dd($userExc);
            throw $userExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::FEE_RECEIPT_LIST_ERROR, $exc);
        }

        return $feeReceipts;
    }

    /**
     * Get list of fee receipts for the patient
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getFeeReceiptsByPatient($patientId)
    {
        $feeReceipts = null;

        try
        {
            $feeReceipts = $this->hospitalRepo->getFeeReceiptsByPatient($patientId);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::FEE_RECEIPT_LIST_ERROR, $exc);
        }

        return $feeReceipts;
    }

    /**
     * Get fee receipt details, doctor details, patient details
     * @param $hospitalId, $doctorId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getReceiptDetails($receiptId)
    {
        $feeReceiptDetails = null;

        try
        {
            $feeReceiptDetails = $this->hospitalRepo->getReceiptDetails($receiptId);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::FEE_RECEIPT_DETAILS_ERROR, $exc);
        }

        return $feeReceiptDetails;
    }

    /**
     * Save fee receipt
     * @param $feeReceiptVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function saveFeeReceipt($feeReceiptVM)
    {
        $status = true;

        try
        {
            DB::transaction(function() use ($feeReceiptVM, &$status)
            {
                $status = $this->hospitalRepo->saveFeeReceipt($feeReceiptVM);
            });

        }
        catch(HospitalException $hospitalExc)
        {
            $status = false;
            throw $hospitalExc;
        }
        catch (Exception $ex) {

            $status = false;
            throw new HospitalException(null, ErrorEnum::FEE_RECEIPT_SAVE_ERROR, $ex);
        }

        return $status;
    }

    /*Symptom section -- Begin */

    /**
     * Get all the symptoms
     * @param none
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getMainSymptoms()
    {
        $mainSymptoms = null;

        try
        {
            $mainSymptoms = $this->hospitalRepo->getMainSymptoms();
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::MAIN_SYMPTOMS_LIST_ERROR, $exc);
        }

        return $mainSymptoms;
    }

    /**
     * Get all the sub symptoms for main symptom
     * @param $mainSymptomsId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getSubSymptomsForMainSymptoms($mainSymptomsId)
    {
        $subSymptoms = null;

        try
        {
            $subSymptoms = $this->hospitalRepo->getSubSymptomsForMainSymptoms($mainSymptomsId);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::SUB_SYMPTOMS_LIST_ERROR, $exc);
        }

        return $subSymptoms;
    }

    /**
     * Get all the symptoms for sub symptom
     * @param $subSymptomId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getSymptomsForSubSymptoms($subSymptomId)
    {
        $symptoms = null;

        try
        {
            $symptoms = $this->hospitalRepo->getSymptomsForSubSymptoms($subSymptomId);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::SYMPTOMS_LIST_ERROR, $exc);
        }

        return $symptoms;
    }

    /**
     * Get personal history for the patient
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPersonalHistory($patientId, $personalHistoryDate)
    {
        $personalHistoryDetails = null;

        try
        {
            $personalHistoryDetails = $this->hospitalRepo->getPersonalHistory($patientId, $personalHistoryDate);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PERSONAL_HISTORY_ERROR, $exc);
        }

        return $personalHistoryDetails;
    }

    /**
     * Get patient past illness
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientPastIllness($patientId, $pastIllnessDate)
    {
        $pastIllness = null;

        try
        {
            $pastIllness = $this->hospitalRepo->getPatientPastIllness($patientId, $pastIllnessDate);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_PAST_ILLNESS_DETAILS_ERROR, $exc);
        }

        return $pastIllness;
    }

    /**
     * Get patient family illness
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientFamilyIllness($patientId, $familyIllnessDate)
    {
        $familyIllness = null;

        try
        {
            $familyIllness = $this->hospitalRepo->getPatientFamilyIllness($patientId, $familyIllnessDate);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_FAMILY_ILLNESS_DETAILS_ERROR, $exc);
        }

        return $familyIllness;
    }

    /**
     * Get patient general examination
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientGeneralExamination($patientId, $generalExaminationDate)
    {
        $generalExamination = null;

        try
        {
            $generalExamination = $this->hospitalRepo->getPatientGeneralExamination($patientId, $generalExaminationDate);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_GENERAL_EXAMINATION_DETAILS_ERROR, $exc);
        }

        return $generalExamination;
    }

    /**
     * Get patient pregnancy details
     * @param $patientId, $pregnancyDate
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPregnancyDetails($patientId, $pregnancyDate)
    {
        $pregnancyDetails = null;

        try
        {
            $pregnancyDetails = $this->hospitalRepo->getPregnancyDetails($patientId, $pregnancyDate);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_PREGNANCY_DETAILS_ERROR, $exc);
        }

        return $pregnancyDetails;
    }

    /**
     * Get patient scan details
     * @param $patientId, $scanDate
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientScanDetails($patientId, $scanDate)
    {
        $scanDetails = null;

        try
        {
            $scanDetails = $this->hospitalRepo->getPatientScanDetails($patientId, $scanDate);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_SCAN_DETAILS_ERROR, $exc);
        }

        return $scanDetails;
    }

    /**
     * Get patient symptom details
     * @param $patientId, $symptomDate
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientSymptoms($patientId, $symptomDate)
    {
        $symptomDetails = null;

        try
        {
            $symptomDetails = $this->hospitalRepo->getPatientSymptoms($patientId, $symptomDate);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_SYMPTOM_DETAILS_ERROR, $exc);
        }

        return $symptomDetails;
    }

    /**
     * Get patient drug history
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientDrugHistory($patientId)
    {
        $drugSurgeryHistory = null;

        try
        {
            $drugSurgeryHistory = $this->hospitalRepo->getPatientDrugHistory($patientId);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_DRUG_HISTORY_ERROR, $exc);
        }

        return $drugSurgeryHistory;
    }

    /**
     * Get patient urine tests
     * @param $patientId, $urineTestDate
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientUrineTests($patientId, $urineTestDate)
    {
        $urineTests = null;

        try
        {
            $urineTests = $this->hospitalRepo->getPatientUrineTests($patientId, $urineTestDate);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_URINE_DETAILS_ERROR, $exc);
        }

        return $urineTests;
    }

    /**
     * Get patient motion tests
     * @param $patientId, $motionTestDate
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientMotionTests($patientId, $motionTestDate)
    {
        $motionTests = null;

        try
        {
            $motionTests = $this->hospitalRepo->getPatientMotionTests($patientId, $motionTestDate);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_MOTION_DETAILS_ERROR, $exc);
        }

        return $motionTests;
    }


    /**
     * Get all family illness
     * @param none
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getAllFamilyIllness()
    {
        $familyIllness = null;

        try
        {
            $familyIllness = $this->hospitalRepo->getAllFamilyIllness();
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::FAMILY_ILLNESS_ERROR, $exc);
        }

        return $familyIllness;
    }

    /**
     * Get all past illness
     * @param none
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getAllPastIllness()
    {
        $pastIllness = null;

        try
        {
            $pastIllness = $this->hospitalRepo->getAllPastIllness();
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PAST_ILLNESS_ERROR, $exc);
        }

        return $pastIllness;
    }

    /**
     * Get all general examinations
     * @param none
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getAllGeneralExaminations()
    {
        $generalExaminations = null;

        try
        {
            $generalExaminations = $this->hospitalRepo->getAllGeneralExaminations();
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::GENERAL_EXAMINATIONS_ERROR, $exc);
        }

        return $generalExaminations;
    }

    /**
     * Get all personal history
     * @param none
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getAllPersonalHistory()
    {
        $personalHistory = null;

        try
        {
            $personalHistory = $this->hospitalRepo->getAllPersonalHistory();
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PERSONAL_HISTORY_LIST_ERROR, $exc);
        }

        return $personalHistory;
    }

    /**
     * Get all pregnancy
     * @param none
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getAllPregnancy()
    {
        $pregnancy = null;

        try
        {
            $pregnancy = $this->hospitalRepo->getAllPregnancy();
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PREGNANCY_LIST_ERROR, $exc);
        }

        return $pregnancy;
    }

    /**
     * Get all scans
     * @param none
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getAllScans()
    {
        $scans = null;

        try
        {
            $scans = $this->hospitalRepo->getAllScans();
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::SCAN_LIST_ERROR, $exc);
        }

        return $scans;
    }

    /**
     * Get patient examination dates
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getExaminationDates($patientId)
    {
        $examinationDates = null;

        try
        {
            $examinationDates = $this->hospitalRepo->getExaminationDates($patientId);
        }
        catch(HospitalException $hospitalExc)
        {
            throw $hospitalExc;
        }
        catch(Exception $exc)
        {
            throw new HospitalException(null, ErrorEnum::PATIENT_EXAMINATION_DATES_ERROR, $exc);
        }

        return $examinationDates;
    }

    /**
     * Save patient personal history
     * @param $patientHistoryVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePersonalHistory($patientHistoryVM)
    {
        $status = true;

        try
        {
            DB::transaction(function() use ($patientHistoryVM, &$status)
            {
                $status = $this->hospitalRepo->savePersonalHistory($patientHistoryVM);
            });

        }
        catch(HospitalException $hospitalExc)
        {
            $status = false;
            throw $hospitalExc;
        }
        catch (Exception $ex) {

            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_PERSONAL_HISTORY_SAVE_ERROR, $ex);
        }

        return $status;
    }

    /**
     * Save patient general examination details
     * @param $patientExaminationVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientGeneralExamination($patientExaminationVM)
    {
        $status = true;

        try
        {
            DB::transaction(function() use ($patientExaminationVM, &$status)
            {
                $status = $this->hospitalRepo->savePatientGeneralExamination($patientExaminationVM);
            });

        }
        catch(HospitalException $hospitalExc)
        {
            $status = false;
            throw $hospitalExc;
        }
        catch (Exception $ex) {

            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_GENERAL_EXAMINATION_SAVE_ERROR, $ex);
        }

        return $status;
    }

    /**
     * Save patient past illness details
     * @param $patientPastIllnessVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientPastIllness($patientPastIllnessVM)
    {
        $status = true;

        try
        {
            DB::transaction(function() use ($patientPastIllnessVM, &$status)
            {
                $status = $this->hospitalRepo->savePatientPastIllness($patientPastIllnessVM);
            });

        }
        catch(HospitalException $hospitalExc)
        {
            $status = false;
            throw $hospitalExc;
        }
        catch (Exception $ex) {

            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_PAST_ILLNESS_SAVE_ERROR, $ex);
        }

        return $status;
    }

    /**
     * Save patient family illness details
     * @param $patientFamilyIllnessVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientFamilyIllness($patientFamilyIllnessVM)
    {
        $status = true;

        try
        {
            DB::transaction(function() use ($patientFamilyIllnessVM, &$status)
            {
                $status = $this->hospitalRepo->savePatientFamilyIllness($patientFamilyIllnessVM);
            });

        }
        catch(HospitalException $hospitalExc)
        {
            $status = false;
            throw $hospitalExc;
        }
        catch (Exception $ex) {

            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_FAMILY_ILLNESS_SAVE_ERROR, $ex);
        }

        return $status;
    }

    /**
     * Save patient pregnancy details
     * @param $patientPregnancyVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientPregnancyDetails($patientPregnancyVM)
    {
        $status = true;

        try
        {
            DB::transaction(function() use ($patientPregnancyVM, &$status)
            {
                $status = $this->hospitalRepo->savePatientPregnancyDetails($patientPregnancyVM);
            });

        }
        catch(HospitalException $hospitalExc)
        {
            $status = false;
            throw $hospitalExc;
        }
        catch (Exception $ex) {

            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_PREGNANCY_DETAILS_SAVE_ERROR, $ex);
        }

        return $status;
    }

    /**
     * Save patient scan details
     * @param $patientScanVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientScanDetails($patientScanVM)
    {
        $status = true;

        try
        {
            DB::transaction(function() use ($patientScanVM, &$status)
            {
                $status = $this->hospitalRepo->savePatientScanDetails($patientScanVM);
            });

        }
        catch(HospitalException $hospitalExc)
        {
            $status = false;
            throw $hospitalExc;
        }
        catch (Exception $ex) {

            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_SCAN_SAVE_ERROR, $ex);
        }

        return $status;
    }

    /**
     * Save patient symptom details
     * @param $patientSymVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientSymptoms($patientSymVM)
    {
        $status = true;

        try
        {
            DB::transaction(function() use ($patientSymVM, &$status)
            {
                $status = $this->hospitalRepo->savePatientSymptoms($patientSymVM);
            });

        }
        catch(HospitalException $hospitalExc)
        {
            $status = false;
            throw $hospitalExc;
        }
        catch (Exception $ex) {

            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_SYMPTOM_SAVE_ERROR, $ex);
        }

        return $status;
    }

    /**
     * Save patient urine examination details
     * @param $patientUrineVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientUrineTests($patientUrineVM)
    {
        $status = true;

        try
        {
            DB::transaction(function() use ($patientUrineVM, &$status)
            {
                $status = $this->hospitalRepo->savePatientUrineTests($patientUrineVM);
            });

        }
        catch(HospitalException $hospitalExc)
        {
            $status = false;
            throw $hospitalExc;
        }
        catch (Exception $ex) {

            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_URINE_DETAILS_SAVE_ERROR, $ex);
        }

        return $status;
    }

    /**
     * Save patient motion examination details
     * @param $patientMotionVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientMotionTests($patientMotionVM)
    {
        $status = true;

        try
        {
            DB::transaction(function() use ($patientMotionVM, &$status)
            {
                $status = $this->hospitalRepo->savePatientMotionTests($patientMotionVM);
            });

        }
        catch(HospitalException $hospitalExc)
        {
            $status = false;
            throw $hospitalExc;
        }
        catch (Exception $ex) {

            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_MOTION_DETAILS_SAVE_ERROR, $ex);
        }

        return $status;
    }

    /**
     * Save patient drug and surgery history
     * @param $patientDrugsVM
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientDrugHistory($patientDrugsVM)
    {
        $status = true;

        try
        {
            DB::transaction(function() use ($patientDrugsVM, &$status)
            {
                $status = $this->hospitalRepo->savePatientDrugHistory($patientDrugsVM);
            });

        }
        catch(HospitalException $hospitalExc)
        {
            $status = false;
            throw $hospitalExc;
        }
        catch (Exception $ex) {

            $status = false;
            throw new HospitalException(null, ErrorEnum::PATIENT_DRUG_HISTORY_SAVE_ERROR, $ex);
        }

        return $status;
    }

    /*Symptom section -- End */
}