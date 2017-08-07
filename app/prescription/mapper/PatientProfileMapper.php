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
use App\Http\ViewModels\PatientDrugHistoryViewModel;
use App\Http\ViewModels\PatientFamilyIllnessViewModel;
use App\Http\ViewModels\PatientGeneralExaminationViewModel;
use App\Http\ViewModels\PatientPastIllnessViewModel;
use App\Http\ViewModels\PatientPersonalHistoryViewModel;
use App\Http\ViewModels\PatientPregnancyViewModel;
use App\Http\ViewModels\PatientProfileViewModel;
use App\Http\ViewModels\PatientScanViewModel;
use App\Http\ViewModels\PatientSymptomsViewModel;
use App\Http\ViewModels\PatientUrineExaminationViewModel;
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
        //$profileVM->setMainSymptomId(property_exists($profile, 'mainSymptomId') ? $profile->mainSymptomId : null);
        //$profileVM->setSubSymptomId(property_exists($profile, 'subSymptomId') ? $profile->subSymptomId : null);
        //$profileVM->setSymptomId(property_exists($profile, 'symptomId') ? $profile->symptomId : null);

        //$symptoms = $profile->$symptoms;
        //dd($candidateEmployments);

        /*foreach($symptoms as $symptom)
        {
            $profileVM->setSymptoms($symptom);
            //$patientPastIllnessVM->setPatientPastIllness($illness);
        }*/

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

    public static function setPatientPastIllness(Request $pastIllnessRequest)
    {
        $patientPastIllnessVM = new PatientPastIllnessViewModel();

        $pastIllnessObj = (object) $pastIllnessRequest->all();
        $patientPastIllnessVM->setPatientId($pastIllnessObj->patientId);
        $pastIllness = $pastIllnessObj->pastIllness;
        //dd($candidateEmployments);

        foreach($pastIllness as $illness)
        {
            $patientPastIllnessVM->setPatientPastIllness($illness);
        }

        //$userName = Session::get('DisplayName');
        $userName = 'Admin';

        $patientPastIllnessVM->setCreatedBy($userName);
        $patientPastIllnessVM->setUpdatedBy($userName);
        $patientPastIllnessVM->setCreatedAt(date("Y-m-d H:i:s"));
        $patientPastIllnessVM->setUpdatedAt(date("Y-m-d H:i:s"));

        return $patientPastIllnessVM;
    }

    public static function setPatientFamilyIllness(Request $familyIllnessRequest)
    {
        $patientFamilyIllnessVM = new PatientFamilyIllnessViewModel();

        $familyIllnessObj = (object) $familyIllnessRequest->all();
        $patientFamilyIllnessVM->setPatientId($familyIllnessObj->patientId);
        $familyIllness = $familyIllnessObj->familyIllness;
        //dd($candidateEmployments);

        foreach($familyIllness as $illness)
        {
            $patientFamilyIllnessVM->setPatientFamilyIllness($illness);
        }

        //$userName = Session::get('DisplayName');
        $userName = 'Admin';

        $patientFamilyIllnessVM->setCreatedBy($userName);
        $patientFamilyIllnessVM->setUpdatedBy($userName);
        $patientFamilyIllnessVM->setCreatedAt(date("Y-m-d H:i:s"));
        $patientFamilyIllnessVM->setUpdatedAt(date("Y-m-d H:i:s"));

        return $patientFamilyIllnessVM;
    }

    public static function setPatientPregnancyDetails(Request $pregnancyRequest)
    {
        $patientPregnancyVM = new PatientPregnancyViewModel();

        $pregnancyObj = (object) $pregnancyRequest->all();
        $patientPregnancyVM->setPatientId($pregnancyRequest->patientId);
        $pregnancyDetails = $pregnancyObj->pregnancyDetails;
        //dd($candidateEmployments);

        foreach($pregnancyDetails as $pregnancy)
        {
            $patientPregnancyVM->setPatientPregnancy($pregnancy);
        }

        //$userName = Session::get('DisplayName');
        $userName = 'Admin';

        $patientPregnancyVM->setCreatedBy($userName);
        $patientPregnancyVM->setUpdatedBy($userName);
        $patientPregnancyVM->setCreatedAt(date("Y-m-d H:i:s"));
        $patientPregnancyVM->setUpdatedAt(date("Y-m-d H:i:s"));

        return $patientPregnancyVM;
    }

    public static function setPatientScanDetails(Request $scanRequest)
    {
        $patientScanVM = new PatientScanViewModel();

        $scanObj = (object) $scanRequest->all();
        $patientScanVM->setPatientId($scanRequest->patientId);
        $scanDetails = $scanObj->scanDetails;
        //dd($candidateEmployments);

        foreach($scanDetails as $scan)
        {
            $patientScanVM->setPatientScans($scan);
        }

        //$userName = Session::get('DisplayName');
        $userName = 'Admin';

        $patientScanVM->setCreatedBy($userName);
        $patientScanVM->setUpdatedBy($userName);
        $patientScanVM->setCreatedAt(date("Y-m-d H:i:s"));
        $patientScanVM->setUpdatedAt(date("Y-m-d H:i:s"));

        return $patientScanVM;
    }

    public static function setPatientSymptomDetails(Request $symptomsRequest)
    {
        $patientSymVM = new PatientSymptomsViewModel();

        $symObj = (object) $symptomsRequest->all();
        $patientSymVM->setPatientId($symptomsRequest->patientId);
        $symptomDetails = $symObj->symptomDetails;
        //dd($candidateEmployments);

        foreach($symptomDetails as $symptom)
        {
            $patientSymVM->setPatientSymptoms($symptom);
        }

        //$userName = Session::get('DisplayName');
        $userName = 'Admin';

        $patientSymVM->setCreatedBy($userName);
        $patientSymVM->setUpdatedBy($userName);
        $patientSymVM->setCreatedAt(date("Y-m-d H:i:s"));
        $patientSymVM->setUpdatedAt(date("Y-m-d H:i:s"));

        return $patientSymVM;
    }

    public static function setPatientDrugHistory(Request $drugHistoryRequest)
    {
        $patientDrugsVM = new PatientDrugHistoryViewModel();

        $drugHistoryObj = (object) $drugHistoryRequest->all();
        $patientDrugsVM->setPatientId($drugHistoryRequest->patientId);
        $drugHistory = $drugHistoryObj->drugHistory;
        $surgeryHistory = $drugHistoryObj->surgeryHistory;
        //dd($candidateEmployments);

        foreach($drugHistory as $history)
        {
            $patientDrugsVM->setDrugHistory($history);
        }

        foreach($surgeryHistory as $history)
        {
            $patientDrugsVM->setSurgeryHistory($history);
        }

        //$userName = Session::get('DisplayName');
        $userName = 'Admin';

        $patientDrugsVM->setCreatedBy($userName);
        $patientDrugsVM->setUpdatedBy($userName);
        $patientDrugsVM->setCreatedAt(date("Y-m-d H:i:s"));
        $patientDrugsVM->setUpdatedAt(date("Y-m-d H:i:s"));

        return $patientDrugsVM;
    }

    public static function setPatientUrineExamination(Request $examinationRequest)
    {
        $patientUrineVM = new PatientUrineExaminationViewModel();

        $examinationObj = (object) $examinationRequest->all();
        $patientUrineVM->setPatientId($examinationRequest->patientId);
        $examinationDetails = $examinationObj->urineExaminations;
        //dd($candidateEmployments);

        foreach($examinationDetails as $examination)
        {
            $patientUrineVM->setExaminations($examination);
        }

        //$userName = Session::get('DisplayName');
        $userName = 'Admin';

        $patientUrineVM->setCreatedBy($userName);
        $patientUrineVM->setUpdatedBy($userName);
        $patientUrineVM->setCreatedAt(date("Y-m-d H:i:s"));
        $patientUrineVM->setUpdatedAt(date("Y-m-d H:i:s"));

        return $patientUrineVM;
    }

    public static function setPatientMotionExamination(Request $examinationRequest)
    {
        $patientMotionVM = new PatientUrineExaminationViewModel();

        $examinationObj = (object) $examinationRequest->all();
        $patientMotionVM->setPatientId($examinationRequest->patientId);
        $examinationDetails = $examinationObj->motionExaminations;
        //dd($candidateEmployments);

        foreach($examinationDetails as $examination)
        {
            $patientMotionVM->setExaminations($examination);
        }

        //$userName = Session::get('DisplayName');
        $userName = 'Admin';

        $patientMotionVM->setCreatedBy($userName);
        $patientMotionVM->setUpdatedBy($userName);
        $patientMotionVM->setCreatedAt(date("Y-m-d H:i:s"));
        $patientMotionVM->setUpdatedAt(date("Y-m-d H:i:s"));

        return $patientMotionVM;
    }

    public static function setPatientBloodExamination(Request $examinationRequest)
    {
        $patientBloodVM = new PatientUrineExaminationViewModel();

        $examinationObj = (object) $examinationRequest->all();
        $patientBloodVM->setPatientId($examinationRequest->patientId);
        $examinationDetails = $examinationObj->bloodExaminations;
        //dd($candidateEmployments);

        foreach($examinationDetails as $examination)
        {
            $patientBloodVM->setExaminations($examination);
        }

        //$userName = Session::get('DisplayName');
        $userName = 'Admin';

        $patientBloodVM->setCreatedBy($userName);
        $patientBloodVM->setUpdatedBy($userName);
        $patientBloodVM->setCreatedAt(date("Y-m-d H:i:s"));
        $patientBloodVM->setUpdatedAt(date("Y-m-d H:i:s"));

        return $patientBloodVM;
    }

    public static function setPatientUltraSoundExamination(Request $examinationRequest)
    {
        $patientUltraSoundVM = new PatientUrineExaminationViewModel();

        $examinationObj = (object) $examinationRequest->all();
        $patientUltraSoundVM->setPatientId($examinationRequest->patientId);
        $examinationDetails = $examinationObj->ultraSoundExaminations;
        //dd($candidateEmployments);

        foreach($examinationDetails as $examination)
        {
            $patientUltraSoundVM->setExaminations($examination);
        }

        //$userName = Session::get('DisplayName');
        $userName = 'Admin';

        $patientUltraSoundVM->setCreatedBy($userName);
        $patientUltraSoundVM->setUpdatedBy($userName);
        $patientUltraSoundVM->setCreatedAt(date("Y-m-d H:i:s"));
        $patientUltraSoundVM->setUpdatedAt(date("Y-m-d H:i:s"));

        return $patientUltraSoundVM;
    }

}