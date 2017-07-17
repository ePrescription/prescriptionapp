<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 01/11/2016
 * Time: 7:34 PM
 */

namespace App\prescription\mapper;

use App\Http\ViewModels\FeeReceiptViewModel;
use App\Http\ViewModels\NewAppointmentViewModel;
use App\Http\ViewModels\PatientGeneralExaminationViewModel;
use App\Http\ViewModels\PatientPersonalHistoryViewModel;
use App\Http\ViewModels\PatientProfileViewModel;
use Illuminate\Http\Request;
use App\Http\Requests\FeeReceiptRequest;
use Session;

class PatientProfileMapper
{
    public static function setPatientProfile(Request $patientProfileRequest)
    {
        $profileVM = new PatientProfileViewModel();
        $profile = (object) $patientProfileRequest->all();

        //$userName = Session::get('DisplayName');
        $userName = 'Admin';

        $profileVM->setPatientId($profile->patientId);
        $profileVM->setName($profile->name);
        $profileVM->setAddress(property_exists($profile, 'address') ? $profile->address : null);
        $profileVM->setCity(property_exists($profile, 'city') ? $profile->city : null);
        $profileVM->setCountry(property_exists($profile, 'country') ? $profile->country : null);
        $profileVM->setTelephone($profile->telephone);
        $profileVM->setEmail(property_exists($profile, 'email') ? $profile->email : null);
        $profileVM->setRelationship(property_exists($profile, 'relationship') ? $profile->relationship : null);
        $profileVM->setSpouseName(property_exists($profile, 'spouseName') ? $profile->spouseName : null);
        $profileVM->setPatientPhoto(property_exists($profile, 'patientPhoto') ? $profile->patientPhoto : null);
        $profileVM->setDob(property_exists($profile, 'dob') ? $profile->dob : null);
        $profileVM->setAge(property_exists($profile, 'age') ? $profile->age : null);
        $profileVM->setPlaceOfBirth(property_exists($profile, 'placeOfBirth') ? $profile->placeOfBirth : null);
        $profileVM->setNationality(property_exists($profile, 'nationality') ? $profile->nationality : null);
        $profileVM->setGender($profile->gender);
        $profileVM->setMaritalStatus(property_exists($profile, 'maritalStatus') ? $profile->maritalStatus : null);
        $profileVM->setHospitalId(property_exists($profile, 'hospitalId') ? $profile->hospitalId : null);
        $profileVM->setDoctorId(property_exists($profile, 'doctorId') ? $profile->doctorId : null);

        $profileVM->setCreatedBy($userName);
        $profileVM->setUpdatedBy($userName);
        $profileVM->setCreatedAt(date("Y-m-d H:i:s"));
        $profileVM->setUpdatedAt(date("Y-m-d H:i:s"));

        return $profileVM;
    }

    public static function setPatientAppointment(Request $patientAppointmentRequest)
    {
        $appointmentVM = new NewAppointmentViewModel();
        $appointment = (object) $patientAppointmentRequest->all();

        //$userName = Session::get('DisplayName');
        $userName = 'Admin';

        $appointmentVM->setPatientId($appointment->patientId);
        $appointmentVM->setHospitalId($appointment->hospitalId);
        $appointmentVM->setDoctorId($appointment->doctorId);
        $appointmentVM->setBriefHistory(trim($appointment->briefHistory));
        $appointmentVM->setAppointmentDate($appointment->appointmentDate);
        $appointmentVM->setAppointmentTime($appointment->appointmentTime);

        $appointmentVM->setCreatedBy($userName);
        $appointmentVM->setUpdatedBy($userName);
        $appointmentVM->setCreatedAt(date("Y-m-d H:i:s"));
        $appointmentVM->setUpdatedAt(date("Y-m-d H:i:s"));

        return $appointmentVM;
    }

    public static function setFeeReceipt(FeeReceiptRequest $feeReceiptRequest)
    {
        $feeReceiptVM = new FeeReceiptViewModel();

        $feeReceipt = (object)$feeReceiptRequest->all();

        $userName = Session::get('DisplayName');
        //$userName = 'Admin';

        $feeReceiptVM->setPatientId($feeReceipt->patientId);
        $feeReceiptVM->setHospitalId($feeReceipt->hospitalId);
        $feeReceiptVM->setDoctorId($feeReceipt->doctorId);
        $feeReceiptVM->setFees($feeReceipt->fees);

        $feeReceiptVM->setCreatedBy($userName);
        $feeReceiptVM->setUpdatedBy($userName);
        $feeReceiptVM->setCreatedAt(date("Y-m-d H:i:s"));
        $feeReceiptVM->setUpdatedAt(date("Y-m-d H:i:s"));

        return $feeReceiptVM;
    }

    public static function setPersonalHistory(Request $personalHistoryRequest)
    {
        $patientHistoryVM = new PatientPersonalHistoryViewModel();

        $patientHistory = (object) $personalHistoryRequest->all();

        $patientHistoryVM->setPatientId($patientHistory->patientId);
        //$patientHistoryVM->setHospitalId($patientHistory->hospitalId);
        //$patientHistoryVM->setDoctorId($patientHistory->doctorId);

        $medicalHistory = $patientHistory->personalHistory;
        //dd($candidateEmployments);

        foreach($medicalHistory as $history)
        {
            $patientHistoryVM->setPatientPersonalHistory($history);
        }

        //$userName = Session::get('DisplayName');
        $userName = 'Admin';

        $patientHistoryVM->setCreatedBy($userName);
        $patientHistoryVM->setUpdatedBy($userName);
        $patientHistoryVM->setCreatedAt(date("Y-m-d H:i:s"));
        $patientHistoryVM->setUpdatedAt(date("Y-m-d H:i:s"));

        return $patientHistoryVM;
    }

    public static function setGeneralExamination(Request $personalExaminationRequest)
    {
        $patientGenExaminationVM = new PatientGeneralExaminationViewModel();

        $generalExaminationObj = (object) $personalExaminationRequest->all();

        $patientGenExaminationVM->setPatientId($generalExaminationObj->patientId);

        $generalExamination = $generalExaminationObj->generalExamination;
        //dd($candidateEmployments);

        foreach($generalExamination as $examination)
        {
            $patientGenExaminationVM->setPatientGeneralExamination($examination);
        }

        //$userName = Session::get('DisplayName');
        $userName = 'Admin';

        $patientGenExaminationVM->setCreatedBy($userName);
        $patientGenExaminationVM->setUpdatedBy($userName);
        $patientGenExaminationVM->setCreatedAt(date("Y-m-d H:i:s"));
        $patientGenExaminationVM->setUpdatedAt(date("Y-m-d H:i:s"));

        return $patientGenExaminationVM;
    }

}