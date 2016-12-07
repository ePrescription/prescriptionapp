<?php

namespace App\Http\Controllers\Doctor;

use App\prescription\common\ResponseJson;
use App\prescription\common\UserSession;
use App\prescription\facades\HospitalServiceFacade;
use App\prescription\mapper\PatientProfileMapper;
use App\prescription\utilities\ErrorEnum\ErrorEnum;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\prescription\services\HelperService;
use App\prescription\services\HospitalService;
use App\prescription\utilities\Exception\HospitalException;
use App\prescription\utilities\Exception\AppendMessage;
use App\prescription\utilities\UserType;
use App\prescription\mapper\PatientPrescriptionMapper;

use App\prescription\model\entities\HospitalDoctor;

use App\Http\Requests\DoctorLoginRequest;
use App\Http\Requests\PatientProfileRequest;
use App\Http\Requests\NewAppointmentRequest;

use App\prescription\mapper\HospitalMapper;

use Log;
use Input;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use Mail;

use App\Http\ViewModels\PatientPrescriptionViewModel;

class DoctorController extends Controller
{
    protected $hospitalService;

    public function __construct(HospitalService $hospitalService) {
        $this->hospitalService = $hospitalService;
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
        $prescriptionResult = null;
        //$result = array();

        try
        {
            //$result['isSuccess'] = 1;
            //$result['message'] =
            $hospitals = HospitalServiceFacade::getHospitals();
            //$result['hospitals'] = $hospitals;
            $msg = trans('messages.SupportTeam');
            //dd($msg);
            $prescriptionResult = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::HOSPITAL_LIST_SUCCESS));
            $prescriptionResult->setObj($hospitals);
            return $prescriptionResult;
            //dd($prescriptionResult);
           //dd(json_encode($prescriptionResult));
            //array2json($hospitals);
        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
            $prescriptionResult = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::HOSPITAL_LIST_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $hospitals;
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
        $prescriptionResult = null;

        try
        {
            //dd('Inside doctor');
            $hospitals = HospitalServiceFacade::getHospitals();
            $prescriptionResult = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::HOSPITAL_LIST_SUCCESS));
            $prescriptionResult->setObj($hospitals);

            //dd($prescriptionResult);
        }
        catch(HospitalException $hospitalExc)
        {
            $prescriptionResult = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::HOSPITAL_LIST_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $prescriptionResult;
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
        $jsonResponse = null;

        try
        {
            //dd('Inside doctor');
            $doctors = HospitalServiceFacade::getDoctorsByHospitalId($hospitalId);
            //dd($doctors);
            if(!empty($doctors))
            {
                $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::HOSPITAL_DOCTOR_LIST_SUCCESS));
                $jsonResponse->setObj($doctors);
            }
            else
            {
                $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::HOSPITAL_NO_DOCTORS_FOUND));
            }

        }
        catch(HospitalException $hospitalExc)
        {
            $prescriptionResult = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::HOSPITAL_DOCTOR_LIST_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $jsonResponse;
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
        $jsonResponse = null;
        //dd('Inside appointment');

        try
        {
            $appointments = HospitalServiceFacade::getAppointmentsByHospitalAndDoctor($hospitalId, $doctorId);
            $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::APPOINTMENT_LIST_SUCCESS));
            $jsonResponse->setObj($appointments);

            //dd($prescriptionResult);
        }
        catch(HospitalException $hospitalExc)
        {
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::APPOINTMENT_LIST_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::APPOINTMENT_LIST_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $jsonResponse;
    }

    //Get Patient List
    /**
     * Get list of patients for the hospital and patient name
     * @param $hospitalId, $keyword
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientsByHospital($hospitalId)
    {
        $patients = null;
        //$jsonResponse = null;
        //$keyword = \Input::get('keyword');
        //dd('Inside patients by hospital');
        try
        {
            //$patients = HospitalServiceFacade::getPatientsByHospital($hospitalId, $keyword);
            $patients = HospitalServiceFacade::getPatientsByHospital($hospitalId);
            $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_LIST_SUCCESS));
            $jsonResponse->setObj($patients);
            //dd($jsonResponse);
        }
        catch(HospitalException $hospitalExc)
        {
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_LIST_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_LIST_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $jsonResponse;
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
        //$jsonResponse = null;
        //dd('Inside patient details');
        try
        {
            $patientDetails = HospitalServiceFacade::getPatientDetailsById($patientId);
            $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_DETAILS_SUCCESS));
            $jsonResponse->setObj($patientDetails);

        }
        catch(HospitalException $hospitalExc)
        {
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_DETAILS_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $jsonResponse;
    }

    /**
     * Login using Email, password and hospital
     * @param $loginRequest
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */


    //public function login()
    //public function login(Request $loginRequest)
    public function login(DoctorLoginRequest $loginRequest)
    {
        //dd('Test');
        $loginInfo = $loginRequest->all();
        $jsonResponse = null;
        $doctorDetails = null;
        //$userSession = null;

        try
        {
           /* $loginDetails['doctor']['id'] = 1;
            $loginDetails['doctor']['name'] = 'Baskar';

            $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::USER_LOGIN_SUCCESS));
            $jsonResponse->setObj($loginDetails);*/

            //dd($jsonResponse);

            //return $jsonResponse;
            if (Auth::attempt(['email' => $loginInfo['email'], 'password' => $loginInfo['password']]))
            {
                if(( Auth::user()->hasRole('doctor')) &&  (Auth::user()->delete_status == 1))
                {
                    $userSession = new UserSession();
                    $userSession->setLoginUserId(Auth::user()->id);
                    $userSession->setDisplayName(ucfirst(Auth::user()->name));
                    $userSession->setLoginUserType(UserType::USERTYPE_DOCTOR);
                    $userSession->setAuthDisplayName(ucfirst(Auth::user()->name));

                    Session::put('loggedUser', $userSession);

                    $userId = Auth::user()->id;
                    $userName = ucfirst(Auth::user()->name);

                    $doctorDetails = HospitalServiceFacade::getDoctorDetails($userId);


                    $loginDetails['doctor']['id'] = $userId;
                    $loginDetails['doctor']['name'] = $userName;
                    $loginDetails['doctor']['details'] = "MBBS MD (Cardiology) 10 years";
                    //$loginDetails['doctor']['details'] = $doctorDetails;

                    $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::USER_LOGIN_SUCCESS));
                    $jsonResponse->setObj($loginDetails);

                }
            }
            else
            {
                $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::DOCTOR_LOGIN_FAILURE));
                //$msg = "Login Details Incorrect! Try Again.";
                //return redirect('hospital/login')->with('message',$msg);
            }

        }
        catch(HospitalException $hospitalExc)
        {
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::DOCTOR_LOGIN_FAILURE));
        }
        catch(Exception $exc)
        {
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::DOCTOR_LOGIN_FAILURE));
        }

        return $jsonResponse;
    }

    public function getDoctorDetails($doctorId)
    {
        $doctorDetails = null;
        $jsonResponse = null;

        try
        {
            //dd($doctorId);
            $doctorDetails = HospitalServiceFacade::getDoctorDetails($doctorId);
            $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::DOCTOR_DETAILS_SUCCESS));
            $jsonResponse->setObj($doctorDetails);
        }
        catch(HospitalException $hospitalExc)
        {
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::DOCTOR_DETAILS_ERROR));
        }
        catch(Exception $exc)
        {
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::DOCTOR_DETAILS_ERROR));
        }

        return $jsonResponse;


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
        //$jsonResponse = null;

        try
        {
            $patientProfile = HospitalServiceFacade::getPatientProfile($patientId);
            $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_PROFILE_SUCCESS));
            $jsonResponse->setObj($patientProfile);
        }
        catch(HospitalException $hospitalExc)
        {
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_PROFILE_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_PROFILE_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }
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
        //$jsonResponse = null;
        //dd('Inside prescriptions');
        try
        {
            $prescriptions = HospitalServiceFacade::getPrescriptions($hospitalId, $doctorId);
            $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PRESCRIPTION_LIST_SUCCESS));
            $jsonResponse->setObj($prescriptions);
        }
        catch(HospitalException $hospitalExc)
        {
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_LIST_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_LIST_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $jsonResponse;
    }

    /**
     * Get list of prescriptions for the patient
     * @param $hospitalId, $doctorId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPrescriptionByPatient($patientId)
    {
        $prescriptions = null;
        //dd('Inside Prescription by patient');

        try
        {
            $prescriptions = HospitalServiceFacade::getPrescriptionByPatient($patientId);
            $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PRESCRIPTION_LIST_SUCCESS));
            $jsonResponse->setObj($prescriptions);
            //dd($jsonResponse);
        }
        catch(HospitalException $hospitalExc)
        {
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_LIST_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_LIST_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $jsonResponse;
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
        //dd('Inside prescription details');

        try
        {
            $prescriptionDetails = HospitalServiceFacade::getPrescriptionDetails($prescriptionId);
            $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SUCCESS));
            $jsonResponse->setObj($prescriptionDetails);
            //dd($jsonResponse);
        }
        catch(HospitalException $hospitalExc)
        {
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $jsonResponse;
    }

    /**
     * Check if a patient is a new patient or follow up patient
     * @param $hospitalId, $doctorId, $patientId
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function checkIsNewPatient(Request $newPatientRequest)
    {
        $hospitalId = $newPatientRequest->get('hospital');
        $doctorId = $newPatientRequest->get('doctor');
        $patientId = $newPatientRequest->get('patient');

        //dd('Hospital Id'.' '.$hospitalId.' Patient Id'.' '.$patientId. ' DoctorId'.' '.$doctorId);

        $isNewPatient = null;

        try
        {
            $isNewPatient = HospitalServiceFacade::checkIsNewPatient($hospitalId, $doctorId, $patientId);
            $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS);
            $jsonResponse->setObj($isNewPatient);
            //dd($jsonResponse);
        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::NEW_PATIENT_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::NEW_PATIENT_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $jsonResponse;
    }

    /**
     * Get prescription details for the patient by prescription Id
     * @param $prescriptionId, $email, $mobile
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPrescriptionDetailsForMail($prescriptionId, $email, $mobile)
    {
        $prescriptionDetails = null;
        //dd('Inside prescription details');

        try
        {
            $prescriptionDetails = HospitalServiceFacade::getPrescriptionDetails($prescriptionId);
            $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SUCCESS));
            $jsonResponse->setObj($prescriptionDetails);
            //dd($jsonResponse);
        }
        catch(HospitalException $hospitalExc)
        {
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $jsonResponse;
    }

    /**
     * Get lab details for the patient by lab Id
     * @param $labId, $email, $mobile
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getLabDetailsForMail($labId, $email, $mobile)
    {
        $labDetails = null;
        //dd('Inside prescription details');

        try
        {
            $labDetails = HospitalServiceFacade::getLabTestDetails($labId);
            $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::LAB_DETAILS_SUCCESS));
            $jsonResponse->setObj($labDetails);
            //dd($jsonResponse);
        }
        catch(HospitalException $hospitalExc)
        {
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LAB_DETAILS_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LAB_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $jsonResponse;
    }

    /**
     * Save patient profile
     * @param $patientProfileRequest
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    //public function savePatientProfile(Request $patientProfileRequest)
    public function savePatientProfile(PatientProfileRequest $patientProfileRequest)
    {
        //return "HI";
        $patientProfileVM = null;
        $status = true;
        $jsonResponse = null;
        //return $patientProfileRequest->all();

        try
        {
            $patientProfileVM = PatientProfileMapper::setPatientProfile($patientProfileRequest);
            $status = HospitalServiceFacade::savePatientProfile($patientProfileVM);

            if($status)
            {
                $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_PROFILE_SAVE_SUCCESS));
            }

        }
        catch(HospitalException $hospitalExc)
        {
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_PROFILE_SAVE_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
            //return $jsonResponse;
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SAVE_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $jsonResponse;
    }

    /**
     * Save new appointment for the patient
     * @param $patientProfileRequest
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    /**
     * Save Prescription for the patient
     * @param
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientPrescription(Request $patientPrescriptionRequest)
    {
        $patientPrescriptionVM = null;
        $status = true;
        $jsonResponse = null;
        //$string = ""

        try
        {

            $patientPrescriptionVM = PatientPrescriptionMapper::setPrescriptionDetails($patientPrescriptionRequest);
            $status = HospitalServiceFacade::savePatientPrescription($patientPrescriptionVM);

            if($status)
            {
                $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SAVE_SUCCESS));
            }

            /*foreach($patientPrescriptionVM->getDrugDetails() as $drug)
            {
                //return $drug;
                $xx = (object) $drug;
                return $xx->dosage;
            }*/

            //return $patientPrescriptionVM
        }
        catch(HospitalException $hospitalExc)
        {
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SAVE_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
            //return $jsonResponse;
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SAVE_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $jsonResponse;
    }

    //Search by Patient Name
    /**
     * Get patient names by keyword
     * @param $keyword
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function searchPatientByName(Request $patientNameRequest)
    {
        $patientNames = null;

        $keyword = $patientNameRequest->get('name');
        //return $keyword;

        try
        {
            $patientNames = HospitalServiceFacade::searchPatientByName($keyword);
            $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_LIST_SUCCESS));
            $jsonResponse->setObj($patientNames);
            //dd($jsonResponse);
        }
        catch(HospitalException $hospitalExc)
        {
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_LIST_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_LIST_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $jsonResponse;
    }

    //Search by Patient Pid
    /**
     * Get patient details by PID
     * @param $pid
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    //public function searchPatientByPid($pid)
    public function searchPatientByPid(Request $patientPidRequest)
    {
        $patient = null;
        //$pid = \Input::get('pid');
        $pid = $patientPidRequest->get('pid');
        //return $pid;


        try
        {
            $patient = HospitalServiceFacade::searchPatientByPid($pid);
            $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_LIST_SUCCESS));
            $jsonResponse->setObj($patient);
        }
        catch(HospitalException $hospitalExc)
        {
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_LIST_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_LIST_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $jsonResponse;
    }

    /**
     * Get patient by Pid or Name
     * @param $patientSearchRequest
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function searchByPatientByPidOrName(Request $patientSearchRequest)
    {
        $patient = null;
        //$pid = \Input::get('pid');
        $keyword = $patientSearchRequest->get('keyword');

        try
        {
            $patient = HospitalServiceFacade::searchByPatientByPidOrName($keyword);
            $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_LIST_SUCCESS));
            $jsonResponse->setObj($patient);
        }
        catch(HospitalException $hospitalExc)
        {
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_LIST_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_LIST_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $jsonResponse;
    }

    //Drugs
    /**
     * Get brand names by keyword
     * @param $keyword
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getBrandNames(Request $brandRequest)
    {
        $brands = null;
        //$keyword = \Input::get('keyword');
        $keyword = $brandRequest->get('brands');
        //$keyword = $brandRequest->get('keyword');
        //$keyword = $brandRequest->all();
        //return $keyword;

        try
        {
            $brands = HospitalServiceFacade::getBrandNames($keyword);
            $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::BRAND_LIST_SUCCESS));
            $jsonResponse->setObj($brands);
        }
        catch(HospitalException $hospitalExc)
        {
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::BRAND_LIST_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::BRAND_LIST_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $jsonResponse;
    }

    //Lab Tests
    /**
     * Get all lab tests
     * @param none
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getLabTests()
    {
        $labTests = null;

        try
        {
            $labTests = HospitalServiceFacade::getLabTests();
            $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::LAB_LIST_SUCCESS));
            $jsonResponse->setObj($labTests);

        }
        catch(HospitalException $hospitalExc)
        {
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LAB_LIST_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LAB_LIST_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $jsonResponse;
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
        //$jsonResponse = null;
        //dd('Inside prescriptions');
        try
        {
            $patientLabTests = HospitalServiceFacade::getLabTestsForPatient($hospitalId, $doctorId);
            //dd()
            $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::LAB_LIST_SUCCESS));
            $jsonResponse->setObj($patientLabTests);
        }
        catch(HospitalException $hospitalExc)
        {
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LAB_LIST_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LAB_LIST_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $jsonResponse;
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
        //dd('Inside Lab test for patient');
        $jsonResponse = null;
        //dd('Inside prescriptions');
        try
        {
            $patientLabTests = HospitalServiceFacade::getLabTestsByPatient($patientId);
            $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS);
            if(empty($patientLabTests))
            {
                $jsonResponse->setMessage(trans('messages.'.ErrorEnum::NO_LABTEST_FOUND));
            }
            else{
                $jsonResponse->setMessage(trans('messages.'.ErrorEnum::LAB_LIST_SUCCESS));
            }
            //dd($patientLabTests);

            $jsonResponse->setObj($patientLabTests);
            //dd($jsonResponse);
        }
        catch(HospitalException $hospitalExc)
        {
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LAB_LIST_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LAB_LIST_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $jsonResponse;
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
        //dd('Inside labtest details');

        try
        {
            $labTestDetails = HospitalServiceFacade::getLabTestDetails($labTestId);
            $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::LAB_DETAILS_SUCCESS));
            $jsonResponse->setObj($labTestDetails);
            //dd($jsonResponse);
        }
        catch(HospitalException $hospitalExc)
        {
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LAB_DETAILS_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LAB_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $jsonResponse;
    }

    /**
     * Save new appointments for the patient
     * @param $patientLabTestRequest
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    //public function saveNewAppointment(Request $appointmentRequest)
    public function saveNewAppointment(NewAppointmentRequest $appointmentRequest)
    {
        $appointmentsVM = null;
        $status = true;
        $jsonResponse = null;

        try
        {
            $appointmentsVM = PatientProfileMapper::setPatientAppointment($appointmentRequest);
            $status = HospitalServiceFacade::saveNewAppointment($appointmentsVM);

            if($status)
            {
                $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_NEW_APPOINTMENT_SUCCESS));
            }
        }
        catch(HospitalException $hospitalExc)
        {
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_NEW_APPOINTMENT_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
            //return $jsonResponse;
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_NEW_APPOINTMENT_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $jsonResponse;
    }

    /**
     * Save labtests for the patient
     * @param $patientLabTestRequest
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function savePatientLabTests(Request $patientLabTestRequest)
    {
        $patientLabTestVM = null;
        $status = true;
        $jsonResponse = null;
        //$string = ""

        try
        {
            $patientLabTestVM = PatientPrescriptionMapper::setLabTestDetails($patientLabTestRequest);
            $status = HospitalServiceFacade::savePatientLabTests($patientLabTestVM);

            if($status)
            {
                $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::LABTESTS_DETAILS_SAVE_SUCCESS));
            }

        }
        catch(HospitalException $hospitalExc)
        {
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LABTESTS_DETAILS_SAVE_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
            //return $jsonResponse;
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LABTESTS_DETAILS_SAVE_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $jsonResponse;
    }


    /**
     * Web Login using Email, password and hospital
     * @param $loginRequest
     * @throws $hospitalException
     * @return array | null
     * @author Vimal
     */

    public function userlogin(Request $loginRequest)
    {
        $loginInfo = $loginRequest->all();
        //dd($loginInfo);
        //$userSession = null;

        try
        {
            if (Auth::attempt(['email' => $loginInfo['email'], 'password' => $loginInfo['password']]))
            {
                //dd(Auth::user());

                /*
                $userSession = new UserSession();
                $userSession->setLoginUserId(Auth::user()->id);
                $userSession->setDisplayName(ucfirst(Auth::user()->name));
                $userSession->setLoginUserType(UserType::USERTYPE_DOCTOR);
                $userSession->setAuthDisplayName(ucfirst(Auth::user()->name));

                Session::put('loggedUser', $userSession);
                */
                //dd(Auth::user());
                $DisplayName=Session::put('DisplayName', ucfirst(Auth::user()->name));
                $LoginUserId=Session::put('LoginUserId', Auth::user()->id);
                $DisplayName=Session::put('DisplayName', ucfirst(Auth::user()->name));
                $AuthDisplayName=Session::put('AuthDisplayName', ucfirst(Auth::user()->name));
                $AuthDisplayPhoto=Session::put('AuthDisplayPhoto', "no-image.jpg");

                if(( Auth::user()->hasRole('hospital')) &&  (Auth::user()->delete_status==1) )
                    {
                        $LoginUserType=Session::put('LoginUserType', 'hospital');
                        return redirect('fronthospital/'.Auth::user()->id.'/dashboard');
                    }
                    else if( Auth::user()->hasRole('doctor')  && (Auth::user()->delete_status==1) )
                    {
                        $LoginUserType=Session::put('LoginUserType', 'doctor');

                        $doctorid = Auth::user()->id;
                        //dd($doctorid);
                        $hospitalId = HospitalServiceFacade::getHospitalId(UserType::USERTYPE_DOCTOR, $doctorid);
                        //dd($hospitalId);
                        Session::put('LoginUserHospital', $hospitalId[0]->hospital_id);
                        return redirect('doctor/'.Auth::user()->id.'/dashboard');
                    }
                    else if( Auth::user()->hasRole('patient') && (Auth::user()->delete_status==1) )
                    {
                        $LoginUserType=Session::put('LoginUserType', 'patient');
                        return redirect('patient/'.Auth::user()->id.'/dashboard');
                    }
                    else if( Auth::user()->hasRole('lab') && (Auth::user()->delete_status==1) )
                    {
                        $LoginUserType=Session::put('LoginUserType', 'lab');
                        //dd($LoginUserType);
                        //GET LAB HOSTPIALID BY LABID
                        $labid = Auth::user()->id;
                        $hospitalId = HospitalServiceFacade::getHospitalId(UserType::USERTYPE_LAB, $labid);

                        Session::put('LoginUserHospital', $hospitalId[0]->hospital_id);
                        return redirect('lab/'.Auth::user()->id.'/dashboard');
                    }
                    else if( Auth::user()->hasRole('pharmacy') && (Auth::user()->delete_status==1) )
                    {
                        $LoginUserType=Session::put('LoginUserType', 'pharmacy');

                        //GET PHARMACY HOSTPIALID BY PHARMACYID
                        $phid = Auth::user()->id;
                        $hospitalId = HospitalServiceFacade::getHospitalId(UserType::USERTYPE_PHARMACY, $phid);
                        //dd($hospitalId[0]->hospital_id);
                        Session::put('LoginUserHospital', $hospitalId[0]->hospital_id);
                        return redirect('pharmacy/'.Auth::user()->id.'/dashboard');
                    }
                    else if(Auth::user()->hasRole('admin'))
                    {
                        $LoginUserType=Session::put('LoginUserType', 'admin');
                        return redirect('admin/'.Auth::user()->id.'/dashboard');
                    }
                    else
                    {
                        Auth::logout();
                        Session::flush();
                        $msg="Login Details Incorrect! Try Again.";
                        return redirect('/login')->with('message',$msg);
                    }

                    //return redirect('hospital/login')->with('message',$msg);

            }
            else
            {
                //$prescriptionResult = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::DOCTOR_LOGIN_FAILURE));
                $msg = "Login Details Incorrect! Try Again.";
                return redirect('/login')->with('message',$msg);
            }

        }
        catch(HospitalException $hospitalExc)
        {
            //dd("1");
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
            $prescriptionResult = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::FAILURE));
        }
        catch(Exception $exc)
        {
            //dd("2".$exc);
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
            $prescriptionResult = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::FAILURE));
        }

    }

    public function getPatientsByHospitalForFront($hospitalId)
    {
        //dd('HI');
        $patients = null;
        try
        {
            $patients = HospitalServiceFacade::getPatientsByHospital($hospitalId);

        }
        catch(HospitalException $hospitalExc)
        {
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);

            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.hospital-patients',compact('patients'));
    }


//VIMAL

    public function getProfile($hospitalId)
    {
        $hospitalProfile = null;
        //dd('Inside get profile function in pharmacy controller');

        try
        {
            $hospitalProfile = $this->hospitalService->getProfile($hospitalId);
            //dd($hospitalProfile);
        }
        catch(HospitalException $profileExc)
        {
            //dd($hospitalExc);
            $errorMsg = $profileExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($profileExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.hospital-profile',compact('hospitalProfile'));

        //return $pharmacyProfile;
    }

    public function editProfile($hospitalId, HelperService $helperService)
    {
        $hospitalProfile = null;
        //dd('Inside get profile function in pharmacy controller');

        try
        {
            $hospitalProfile = $this->hospitalService->getProfile($hospitalId);
            //dd($hospitalProfile);
            $cities = $helperService->getCities();
            //dd($cities);
        }
        catch(HospitalException $profileExc)
        {
            //dd($hospitalExc);
            $errorMsg = $profileExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($profileExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.hospital-editprofile',compact('hospitalProfile','cities'));

        //return $pharmacyProfile;
    }


    public function editHospital(Request $hospitalRequest)
    {


        //$pharmacyVM = null;
        //$status = true;
        //$string = ""
        //dd($pharmacyRequest);
        try
        {
            $hospitalId = Auth::user()->id;
            $hospitalProfile = $this->hospitalService->getProfile($hospitalId);
            $message= "Profile Details Updated Successfully";


            /*$hospitalVM = HospitalMapper::setPhamarcyDetails($hospitalRequest);
            //dd($pharmacyVM);
            $status = $this->pharmacyService->editPharmacy($pharmacyVM);
            //dd($status);

            /*if($status)
            {
                //$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SAVE_SUCCESS));
            }*/

            /*if($status) {
                $pharmacyId=$pharmacyVM->getPharmacyId();
                //dd($pharmacyId);
                $pharmacyProfile = $this->pharmacyService->getProfile($pharmacyId);
                $message= "Profile Details Updated Successfully";
            }*/

        }
        catch(HospitalException $profileExc)
        {
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SAVE_ERROR));
            $errorMsg = $profileExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($profileExc);
            Log::error($msg);
            //return $jsonResponse;
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SAVE_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.hospital-profile',compact('hospitalProfile','message'));
        // dd($pharmacyProfile);
        //return view('portal.pharmacy-profile',compact('pharmacyProfile','message'));

        //return $jsonResponse;
    }


    public function editChangePassword($pharmacyId)
    {
        $pharmacyProfile = null;
        //dd('Inside get profile function in pharmacy controller');

        try
        {
            //$pharmacyProfile = $this->pharmacyService->getProfile($pharmacyId);
            //dd($pharmacyProfile);
        }
        catch(PharmacyException $profileExc)
        {
            //dd($hospitalExc);
            $errorMsg = $profileExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($profileExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.hospital-changepassword');

        //return $pharmacyProfile;
    }


    public function addPatientsByHospitalForFront($hospitalId)
    {
        //dd('HI');
        $patients = null;
        try
        {
            //$patients = HospitalServiceFacade::getPatientsByHospital($hospitalId);

        }
        catch(HospitalException $hospitalExc)
        {
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);

            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.hospital-addpatient');
    }


    public function savePatientsByHospitalForFront(Request $patientProfileRequest)
    {
        //dd('HI');
        //return "HI";
        $patientProfileVM = null;
        $status = true;
        $jsonResponse = null;
        //return $patientProfileRequest->all();

        try
        {
            $patientProfileVM = PatientProfileMapper::setPatientProfile($patientProfileRequest);
            $status = HospitalServiceFacade::savePatientProfile($patientProfileVM);

            if($status)
            {
                //$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_PROFILE_SAVE_SUCCESS));

                $msg = "Patient Profile Added Successfully.";
                return redirect('fronthospital/rest/api/'.Auth::user()->id.'/addpatient')->with('success',$msg);
            }
            else
            {
                $msg = "Patient Details Invalid / Incorrect! Try Again.";
                return redirect('fronthospital/rest/api/'.Auth::user()->id.'/addpatient')->with('message',$msg);
            }

        }
        catch(HospitalException $hospitalExc)
        {
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_PROFILE_SAVE_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
            //return $jsonResponse;
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SAVE_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        $msg = "Patient Details Invalid / Incorrect! Try Again.";
        return redirect('fronthospital/rest/api/'.Auth::user()->id.'/addpatient')->with('message',$msg);
        //return $jsonResponse;

    }



    public function addAppointmentByHospitalForFront($hospitalId,$patientId)
    {
        //dd('HI');
        $doctors = null;
        $patientProfile = null;
        try
        {
            //dd($patientId);
            $doctors = HospitalServiceFacade::getDoctorsByHospitalId($hospitalId);
            //$patientDetails = HospitalServiceFacade::getPatientDetailsById($patientId);
            $patientProfile = HospitalServiceFacade::getPatientProfile($patientId);
            //dd($doctors);
        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);

            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.hospital-addappointment',compact('doctors','patientProfile'));
    }


    public function saveAppointmentByHospitalForFront(Request $appointmentRequest)
    {
        //dd($appointmentRequest);
        $appointmentsVM = null;
        $status = true;
        $jsonResponse = null;

        try
        {
            $appointmentsVM = PatientProfileMapper::setPatientAppointment($appointmentRequest);
            $status = HospitalServiceFacade::saveNewAppointment($appointmentsVM);

            if($status)
            {
                //$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_PROFILE_SAVE_SUCCESS));

                $msg = "Patient Appointment Added Successfully.";
                return redirect('fronthospital/rest/api/'.Auth::user()->id.'/patients')->with('success',$msg);
            }
            else
            {
                $msg = "Patient Appointment Details Invalid / Incorrect! Try Again.";
                return redirect('fronthospital/rest/api/'.Auth::user()->id.'/patients')->with('message',$msg);
            }

        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_PROFILE_SAVE_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
            //return $jsonResponse;
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SAVE_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        $msg = "Patient Appointment Details Invalid / Incorrect! Try Again.";
        return redirect('fronthospital/rest/api/'.Auth::user()->id.'/patients')->with('message',$msg);
        //return $jsonResponse;

    }



    public function getPatientsByDoctorForFront($doctorId,$hospitalId)
    {
        //dd('HI');
        $patients = null;
        try
        {
            //$hospitalInfo = HospitalDoctor::where('doctor_id','=',$doctorId)->first();
            //$hospitalId=$hospitalInfo['hospital_id'];

            //dd($hospitalId);
            $patients = HospitalServiceFacade::getPatientsByHospital($hospitalId);

        }
        catch(HospitalException $hospitalExc)
        {
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);

            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.doctor-patients',compact('patients'));
    }

    public function getPatientAllDetails($patientId)
    {
        $patientDetails = null;
        $patientPrescriptions = null;
        $labTests = null;
        //$jsonResponse = null;
        //dd('Inside patient details');
        try
        {
            $patientDetails = HospitalServiceFacade::getPatientDetailsById($patientId);
            $patientPrescriptions = HospitalServiceFacade::getPrescriptionByPatient($patientId);
            $labTests = HospitalServiceFacade::getLabTestsByPatient($patientId);
        }
        catch(HospitalException $hospitalExc)
        {
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_DETAILS_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        //Modify to return to the appropriate view
        return 'test';
        //return $jsonResponse;
    }

    public function sendEmail(Request $mailRequest)
    {
        //dd($mailRequest);

        $title = $mailRequest->input('title');
        $content = $mailRequest->input('content');

        $data = array('name' => "Learning laravel", 'title' => $title, 'content' => $content);

        //return response()->json($title);

        try
        {

            Mail::send('emails.send', $data, function ($m) {
                $m->from('baskar@gmail.com', 'Learning Laravel');

                $m->to('baskar2271@yahoo.com')->subject('Learning laravel test mail');
            });
        }
        catch(Exception $exc)
        {
            return response()->json(['message' => $exc->getMessage()]);
        }

        return response()->json(['message' => 'Request completed']);
    }
}
