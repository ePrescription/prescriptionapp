<?php

namespace App\Http\Controllers\Doctor;

use App\prescription\common\ResponseJson;
use App\prescription\common\ResponsePrescription;
use App\prescription\common\UserSession;
use App\prescription\facades\HospitalServiceFacade;
use App\prescription\mapper\PatientProfileMapper;
use App\prescription\utilities\ErrorEnum\ErrorEnum;
use App\prescription\utilities\Exception\UserNotFoundException;
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
use App\Http\Requests\EditPatientProfileRequest;
use App\Http\Requests\NewAppointmentRequest;
use App\Http\Requests\FeeReceiptRequest;

use App\prescription\mapper\HospitalMapper;

use Log;
use Input;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\DB;

use Mail;
use GuzzleHttp\Client;

use App\Http\ViewModels\PatientPrescriptionViewModel;

use Softon\Indipay\Facades\Indipay;


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
        $responseJson = null;
        //$result = array();

        try
        {
            //$hospitals = HospitalServiceFacade::getHospitals();
            $hospitals = $this->hospitalService->getHospitals();

            if(!empty($hospitals))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::HOSPITAL_LIST_SUCCESS));
                $responseJson->setCount(sizeof($hospitals));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_HOSPITAL_LIST_FOUND));
            }

            $responseJson->setObj($hospitals);
            $responseJson->sendSuccessResponse();
            //return $prescriptionResult;
        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::HOSPITAL_LIST_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
            /*$errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);*/
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$msg = AppendMessage::appendGeneralException($exc);
            //Log::error($msg);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::HOSPITAL_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
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
        //$prescriptionResult = null;
        $responseJson = null;

        try
        {
            //dd('Inside doctor');
            //$hospitals = HospitalServiceFacade::getHospitals();
            $hospitals = $this->hospitalService->getHospitals();

            if(!empty($hospitals))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::HOSPITAL_LIST_SUCCESS));
                $responseJson->setCount(sizeof($hospitals));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_HOSPITAL_LIST_FOUND));
            }

            $responseJson->setObj($hospitals);
            $responseJson->sendSuccessResponse();
            //$prescriptionResult = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::HOSPITAL_LIST_SUCCESS));
            //$prescriptionResult->setObj($hospitals);

            //dd($prescriptionResult);
        }
        catch(HospitalException $hospitalExc)
        {
            //$prescriptionResult = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::HOSPITAL_LIST_ERROR));
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::HOSPITAL_LIST_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
            /*$errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);*/
        }
        catch(Exception $exc)
        {
            //dd($exc);
            /*$responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::HOSPITAL_LIST_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);*/
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::HOSPITAL_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
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
        //$jsonResponse = null;
        $responseJson = null;
        $count = 0;

        try
        {
            $doctors = $this->hospitalService->getDoctorsByHospitalId($hospitalId);

            if(!empty($doctors))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::HOSPITAL_DOCTOR_LIST_SUCCESS));
                $responseJson->setCount(sizeof($doctors));
                /*$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::HOSPITAL_DOCTOR_LIST_SUCCESS));
                $jsonResponse->setObj($doctors);*/
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::HOSPITAL_NO_DOCTORS_FOUND));
                //$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::HOSPITAL_NO_DOCTORS_FOUND));
            }

            //$responseJson->setCount($count);
            $responseJson->setObj($doctors);
            $responseJson->sendSuccessResponse();

        }
        catch(HospitalException $hospitalExc)
        {
            //$prescriptionResult = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::HOSPITAL_DOCTOR_LIST_ERROR));
            /*$responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::HOSPITAL_DOCTOR_LIST_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);*/
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::HOSPITAL_DOCTOR_LIST_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            /*$responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::HOSPITAL_DOCTOR_LIST_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);*/
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::HOSPITAL_DOCTOR_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        //return $jsonResponse;
        return $responseJson;
    }

    /**
     * Get list of hospitals for the doctor
     * @param $email
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getHospitalsForDoctor(Request $request)
    {
        $hospitals = null;
        $email = $request->get('email');
        //$jsonResponse = null;
        $responseJson = null;
        //$count = 0;

        try
        {
            $hospitals = $this->hospitalService->getHospitalsForDoctor($email);

            if(!empty($hospitals))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::HOSPITAL_LIST_SUCCESS));
                $responseJson->setCount(sizeof($hospitals));
                /*$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::HOSPITAL_DOCTOR_LIST_SUCCESS));
                $jsonResponse->setObj($doctors);*/
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_HOSPITALS_FOUND));
                //$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::HOSPITAL_NO_DOCTORS_FOUND));
            }

            //$responseJson->setCount($count);
            $responseJson->setObj($hospitals);
            $responseJson->sendSuccessResponse();

        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::HOSPITAL_LIST_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::HOSPITAL_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        //return $jsonResponse;
        return $responseJson;
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
        //$jsonResponse = null;
        $responseJson = null;

        try
        {
            //$appointments = HospitalServiceFacade::getAppointmentsByHospitalAndDoctor($hospitalId, $doctorId);
            $appointments = $this->hospitalService->getAppointmentsByHospitalAndDoctor($hospitalId, $doctorId);

            if(!empty($appointments))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::APPOINTMENT_LIST_SUCCESS));
                $responseJson->setCount(sizeof($appointments));
                /*$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::HOSPITAL_DOCTOR_LIST_SUCCESS));
                $jsonResponse->setObj($doctors);*/
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_APPOINTMENT_LIST_FOUND));
                //$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::HOSPITAL_NO_DOCTORS_FOUND));
            }

            $responseJson->setObj($appointments);
            $responseJson->sendSuccessResponse();

            //dd($prescriptionResult);
        }
        catch(HospitalException $hospitalExc)
        {
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::APPOINTMENT_LIST_ERROR));
            /*$responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::APPOINTMENT_LIST_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);*/
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::APPOINTMENT_LIST_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            /*$responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::APPOINTMENT_LIST_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);*/
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::APPOINTMENT_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        //return $jsonResponse;
        return $responseJson;
    }

    //Get Patient List
    /**
     * Get list of patients for the hospital and patient name
     * @param $hospitalId, $keyword
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientsByHospital($hospitalId, Request $patientRequest)
    {
        $patients = null;
        $responseJson = null;
        //$jsonResponse = null;
        $keyword = $patientRequest->get('keyword');
        //dd('Inside patients by hospital');
        try
        {
            $patients = $this->hospitalService->getPatientsByHospital($hospitalId, $keyword);

            if(!empty($patients))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_LIST_SUCCESS));
                $responseJson->setCount(sizeof($patients));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_PATIENT_LIST_FOUND));
            }

            $responseJson->setObj($patients);
            $responseJson->sendSuccessResponse();

        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_LIST_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
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
        $responseJson = null;
        //$jsonResponse = null;

        //dd('Inside patient details '.$patientId);
        try
        {
            //$patientDetails = HospitalServiceFacade::getPatientDetailsById($patientId);
            $patientDetails = $this->hospitalService->getPatientDetailsById($patientId);

            if(!empty($patientDetails))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_DETAILS_SUCCESS));
                $responseJson->setCount(sizeof($patientDetails));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_PATIENT_DETAILS_FOUND));
            }

            $responseJson->setObj($patientDetails);
            $responseJson->sendSuccessResponse();

        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_DETAILS_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_DETAILS_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
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
        $responseJson = null;
        //$userSession = null;

        try
        {
           /* $loginDetails['doctor']['id'] = 1;
            $loginDetails['doctor']['name'] = 'Baskar';

            $jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::USER_LOGIN_SUCCESS));
            $jsonResponse->setObj($loginDetails);*/

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
                    $hospitals = $this->hospitalService->getHospitalsByDoctorId($userId);

                    /*$loginDetails['doctor']['id'] = $userId;
                    $loginDetails['doctor']['name'] = $userName;
                    //$loginDetails['doctor']['details'] = "MBBS MD (Cardiology) 10 years";
                    $loginDetails['doctor']['details'] = $doctorDetails;*/

                    //$details = (object)$doctorDetails;

                    $loginDetails['id'] = $userId;
                    $loginDetails['name'] = $userName;
                    $loginDetails['department'] = $doctorDetails[0]->department;
                    $loginDetails['designation'] = $doctorDetails[0]->designation;
                    $loginDetails['hospitals'] = $hospitals;
                    //$loginDetails['dept'] = $doctorDetails[0]->doctorId;
                    //$doctorDetails['department'] =
                    //$loginDetails['department'] = $doctorDetails['department'];
                    //dd($doctorDetails->department);
                    //$loginDetails['department'] = $doctorDetails['details'];

                    $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::USER_LOGIN_SUCCESS));
                    $responseJson->setCount(sizeof($doctorDetails));
                    $responseJson->setObj($loginDetails);
                    $responseJson->sendSuccessResponse();

                    /*$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::USER_LOGIN_SUCCESS));
                    $jsonResponse->setObj($loginDetails);*/

                }
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::DOCTOR_LOGIN_FAILURE));
                $responseJson->sendSuccessResponse();
                //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::DOCTOR_LOGIN_FAILURE));
                //$msg = "Login Details Incorrect! Try Again.";
                //return redirect('hospital/login')->with('message',$msg);
            }

        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::DOCTOR_LOGIN_FAILURE));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::DOCTOR_LOGIN_FAILURE));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
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
        $responseJson = null;
        //$jsonResponse =
        //dd($doctorId);

        //dd('Inside patient details '.$patientId);
        try
        {
            //$patientDetails = HospitalServiceFacade::getPatientDetailsById($patientId);
            $hospitals = $this->hospitalService->getHospitalsByDoctorId($doctorId);
            //dd($hospitals);

            if(!empty($hospitals))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::HOSPITAL_LIST_SUCCESS));
                $responseJson->setCount(sizeof($hospitals));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_HOSPITALS_FOUND));
            }

            $responseJson->setObj($hospitals);
            $responseJson->sendSuccessResponse();

        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::HOSPITAL_LIST_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::HOSPITAL_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
    }


    public function doctorloginform()
    {
        //dd('HI');
        $feeReceiptDetails = null;
        $responseJson = null;
        $hospitals = null;

        try
        {
            $hospitals = $this->hospitalService->getHospitals();
        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
            //$responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::FEE_RECEIPT_DETAILS_ERROR));
            //$responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::FEE_RECEIPT_DETAILS_ERROR));
            //$responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return view('portal.doctor-login',compact('hospitals'));
        //return $responseJson;
    }

    public function doctorlogin(DoctorLoginRequest $loginRequest)
    {
        //dd('Test');
        $loginInfo = $loginRequest->all();
        //dd($loginInfo);
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


                if( Auth::user()->hasRole('doctor')  && (Auth::user()->delete_status==1) )
                {
                    $LoginUserType=Session::put('LoginUserType', 'doctor');

                    $doctorid = Auth::user()->id;
                    //dd($doctorid);
                    //$hospitalId = HospitalServiceFacade::getHospitalId(UserType::USERTYPE_DOCTOR, $doctorid);
                    //dd($hospitalId);

                    Session::put('LoginUserHospital', $loginInfo['hospital']);

                    $hospitalInfo = HospitalServiceFacade::getProfile($loginInfo['hospital']);
                    //dd($hospitalInfo);
                    Session::put('LoginHospitalDetails', $hospitalInfo[0]->hospital_name.' '.$hospitalInfo[0]->address);

                    $doctorInfo = HospitalServiceFacade::getDoctorDetails($doctorid);
                    //dd($doctorInfo);
                    Session::put('LoginDoctorDetails', $doctorInfo[0]->doctorDetails);

                    return redirect('doctor/'.Auth::user()->id.'/dashboard');
                }
                else
                {
                    Auth::logout();
                    Session::flush();
                    $msg="Login Details Incorrect! Please try Again.";
                    return redirect('/doctor/login')->with('message',$msg);
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

    public function getDoctorDetails($doctorId)
    {
        $doctorDetails = null;
        //$jsonResponse = null;
        $responseJson = null;
        //dd($doctorId);

        try
        {
            //$doctorDetails = HospitalServiceFacade::getDoctorDetails($doctorId);
            $doctorDetails = $this->hospitalService->getDoctorDetails($doctorId);
            //dd($doctorDetails);

            if(!empty($doctorDetails))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::DOCTOR_DETAILS_SUCCESS));
                $responseJson->setCount(sizeof($doctorDetails));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_DOCTOR_DETAILS_FOUND));
            }

            $responseJson->setObj($doctorDetails);
            $responseJson->sendSuccessResponse();


            /*$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::DOCTOR_DETAILS_SUCCESS));
            $jsonResponse->setObj($doctorDetails);*/
        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::DOCTOR_DETAILS_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::DOCTOR_DETAILS_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        //return $jsonResponse;
        return $responseJson;
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
        $responseJson = null;

        try
        {
            //$patientProfile = HospitalServiceFacade::getPatientProfile($patientId);
            $patientProfile = $this->hospitalService->getPatientProfile($patientId);

            if(!empty($patientProfile))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_PROFILE_SUCCESS));
                $responseJson->setCount(sizeof($patientProfile));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_PATIENT_PROFILE_FOUND));
            }

            $responseJson->setObj($patientProfile);
            $responseJson->sendSuccessResponse();
            //$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_PROFILE_SUCCESS));
            //$jsonResponse->setObj($patientProfile);
        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_PROFILE_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_PROFILE_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
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
        $responseJson = null;
        //$jsonResponse = null;
        try
        {
            //$prescriptions = HospitalServiceFacade::getPrescriptions($hospitalId, $doctorId);
            $prescriptions = $this->hospitalService->getPrescriptions($hospitalId, $doctorId);

            if(!empty($prescriptions))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PRESCRIPTION_LIST_SUCCESS));
                $responseJson->setCount(sizeof($prescriptions));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_PRESCRIPTION_LIST_FOUND));
            }

            $responseJson->setObj($prescriptions);
            $responseJson->sendSuccessResponse();


            /*$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PRESCRIPTION_LIST_SUCCESS));
            $jsonResponse->setObj($prescriptions);*/
        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_LIST_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);

        }
        catch(Exception $exc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        //return $jsonResponse;
        return $responseJson;
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
        $responseJson = null;
        $responseJson = null;
        //$jsonResponse = null;

        try
        {
            //$prescriptions = HospitalServiceFacade::getPrescriptionByPatient($patientId);
            $prescriptions = $this->hospitalService->getPrescriptionByPatient($patientId);

            if(!empty($prescriptions))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PRESCRIPTION_LIST_SUCCESS));
                $responseJson->setCount(sizeof($prescriptions));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_PRESCRIPTION_LIST_FOUND));
            }

            $responseJson->setObj($prescriptions);
            $responseJson->sendSuccessResponse();

            /*$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PRESCRIPTION_LIST_SUCCESS));
            $jsonResponse->setObj($prescriptions);*/
        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_LIST_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        //return $jsonResponse;
        return $responseJson;
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
        $responseJson = null;
        //$jsonResponse = null;

        try
        {
            //$prescriptionDetails = HospitalServiceFacade::getPrescriptionDetails($prescriptionId);
            $prescriptionDetails = $this->hospitalService->getPrescriptionDetails($prescriptionId);

            if(!empty($prescriptionDetails))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SUCCESS));
                $responseJson->setCount(sizeof($prescriptionDetails));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::NO_PRESCRIPTION_DETAILS_FOUND));
            }

            $responseJson->setObj($prescriptionDetails);
            $responseJson->sendSuccessResponse();

            /*$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SUCCESS));
            $jsonResponse->setObj($prescriptionDetails);*/
        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $responseJson->sendErrorResponse($exc);
        }

        //return $jsonResponse;
        return $responseJson;
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
        $responseJson = null;
        $jsonResponse = null;

        $hospitalId = $newPatientRequest->get('hospital');
        $doctorId = $newPatientRequest->get('doctor');
        $patientId = $newPatientRequest->get('patient');

        //dd('Hospital Id'.' '.$hospitalId.' Patient Id'.' '.$patientId. ' DoctorId'.' '.$doctorId);

        $isNewPatient = null;

        try
        {
            //$isNewPatient = HospitalServiceFacade::checkIsNewPatient($hospitalId, $doctorId, $patientId);
            $isNewPatient = $this->hospitalService->checkIsNewPatient($hospitalId, $doctorId, $patientId);

            $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS);
            $responseJson->setObj($isNewPatient);
            $responseJson->sendSuccessResponse();

        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::NEW_PATIENT_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::NEW_PATIENT_ERROR));
            $responseJson->sendErrorResponse($exc);
        }

        //return $jsonResponse;
        return $responseJson;
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
        $responseJson = null;
        //dd('Inside prescription details');

        try
        {
            //$prescriptionDetails = HospitalServiceFacade::getPrescriptionDetails($prescriptionId);
            $prescriptionDetails = $this->hospitalService->getPrescriptionDetails($prescriptionId);

            if(!empty($prescriptionDetails))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SUCCESS));
                $responseJson->setCount(sizeof($prescriptionDetails));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_PRESCRIPTION_DETAILS_FOUND));
            }

            $responseJson->setObj($prescriptionDetails);
            $responseJson->sendSuccessResponse();
        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $responseJson->sendErrorResponse($exc);
        }

        return $responseJson;
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
        $responseJson = null;
        //dd('Inside prescription details');

        try
        {
            //$labDetails = HospitalServiceFacade::getLabTestDetails($labId);
            $labDetails = $this->hospitalService->getLabTestDetails($labId);

            if(!empty($labDetails))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::LAB_DETAILS_SUCCESS));
                $responseJson->setCount(sizeof($labDetails));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_LAB_DETAILS_FOUND));
            }

            $responseJson->setObj($labDetails);
            $responseJson->sendSuccessResponse();
            //dd($jsonResponse);
        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LAB_DETAILS_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LAB_DETAILS_ERROR));
            $responseJson->sendErrorResponse($exc);
        }

        return $responseJson;
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
        $responseJson = null;
        //return $patientProfileRequest->all();

        try
        {
            $patientProfileVM = PatientProfileMapper::setPatientProfile($patientProfileRequest);
            $status = $this->hospitalService->savePatientProfile($patientProfileVM);

            //$status = HospitalServiceFacade::savePatientProfile($patientProfileVM);
            //$patient = HospitalServiceFacade::savePatientProfile($patientProfileVM);

            if($status)
            {
                //$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_PROFILE_SAVE_SUCCESS));
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_PROFILE_SAVE_SUCCESS));
                $responseJson->sendSuccessResponse();
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_PROFILE_SAVE_ERROR));
                $responseJson->sendSuccessResponse();
            }

        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.$hospitalExc->getUserErrorCode()));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        /*catch(UserNotFoundException $userExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.$userExc->getUserErrorCode()));
            $responseJson->sendErrorResponse($userExc);
        }*/
        catch(Exception $exc)
        {
            //dd($exc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_PROFILE_SAVE_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
    }

    /**
     * Save patient profile
     * @param $patientProfileRequest
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    //public function savePatientProfile(Request $patientProfileRequest)
    public function editPatientProfile(EditPatientProfileRequest $patientProfileRequest)
    {
        //return "HI";
        $patientProfileVM = null;
        $status = true;
        $responseJson = null;
        //return $patientProfileRequest->all();

        try
        {
            $patientProfileVM = PatientProfileMapper::setPatientProfile($patientProfileRequest);
            $status = $this->hospitalService->savePatientProfile($patientProfileVM);

            //$status = HospitalServiceFacade::savePatientProfile($patientProfileVM);
            //$patient = HospitalServiceFacade::savePatientProfile($patientProfileVM);

            if($status)
            {
                //$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_PROFILE_SAVE_SUCCESS));
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_PROFILE_SAVE_SUCCESS));
                $responseJson->sendSuccessResponse();
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_PROFILE_SAVE_ERROR));
                $responseJson->sendSuccessResponse();
            }

        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.$hospitalExc->getUserErrorCode()));
            $responseJson->sendErrorResponse($hospitalExc);
        }
            /*catch(UserNotFoundException $userExc)
            {
                $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.$userExc->getUserErrorCode()));
                $responseJson->sendErrorResponse($userExc);
            }*/
        catch(Exception $exc)
        {
            //dd($exc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_PROFILE_SAVE_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
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
        $responseJson = null;
        //$string = ""

        try
        {
            $patientPrescriptionVM = PatientPrescriptionMapper::setPrescriptionDetails($patientPrescriptionRequest);
            //$status = HospitalServiceFacade::savePatientPrescription($patientPrescriptionVM);
            $status = $this->hospitalService->savePatientPrescription($patientPrescriptionVM);

            if($status)
            {
                //$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_PROFILE_SAVE_SUCCESS));
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SAVE_SUCCESS));
                $responseJson->sendSuccessResponse();
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SAVE_ERROR));
                $responseJson->sendSuccessResponse();
            }

            //return $patientPrescriptionVM
        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SAVE_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
            //return $jsonResponse;
        }
        catch(Exception $exc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SAVE_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
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
        $responseJson = null;

        $keyword = $patientNameRequest->get('name');
        //return $keyword;

        try
        {
            //$patientNames = HospitalServiceFacade::searchPatientByName($keyword);
            $patientNames = $this->hospitalService->searchPatientByName($keyword);

            if(!empty($patientNames))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_LIST_SUCCESS));
                $responseJson->setCount(sizeof($patientNames));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_PATIENT_LIST_FOUND));
            }

            $responseJson->setObj($patientNames);
            $responseJson->sendSuccessResponse();
            //dd($jsonResponse);
        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_LIST_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
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
        $responseJson = null;
        //$pid = \Input::get('pid');
        $pid = $patientPidRequest->get('pid');
        //return $pid;
        try
        {
            //$patient = HospitalServiceFacade::searchPatientByPid($pid);
            $patient = $this->hospitalService->searchPatientByPid($pid);

            if(!empty($patient))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_LIST_SUCCESS));
                $responseJson->setCount(sizeof($patient));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_PATIENT_LIST_FOUND));
            }

            $responseJson->setObj($patient);
            $responseJson->sendSuccessResponse();
            //$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_LIST_SUCCESS));
            //$jsonResponse->setObj($patient);
        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_LIST_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
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
        $responseJson = null;
        //$pid = \Input::get('pid');
        $keyword = $patientSearchRequest->get('keyword');

        try
        {
            //$patient = HospitalServiceFacade::searchByPatientByPidOrName($keyword);
            $patient = $this->hospitalService->searchByPatientByPidOrName($keyword);

            if(!empty($patient))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_LIST_SUCCESS));
                $responseJson->setCount(sizeof($patient));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_PATIENT_LIST_FOUND));
            }

            $responseJson->setObj($patient);
            $responseJson->sendSuccessResponse();


        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_LIST_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
    }

    //Drugs
    /**
     * Get brand names by keyword
     * @param $keyword
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getTradeNames(Request $brandRequest)
    {
        $brands = null;
        $responseJson = null;
        //$keyword = \Input::get('keyword');
        $keyword = $brandRequest->get('brands');
        //$keyword = $brandRequest->get('keyword');
        //$keyword = $brandRequest->all();

        try
        {
            //$brands = HospitalServiceFacade::getBrandNames($keyword);
            $brands = $this->hospitalService->getTradeNames($keyword);

            if(!empty($brands))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::BRAND_LIST_SUCCESS));
                $responseJson->setCount(sizeof($brands));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_BRAND_LIST_FOUND));
            }

            $responseJson->setObj($brands);
            $responseJson->sendSuccessResponse();
        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::BRAND_LIST_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::BRAND_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
    }

    /**
     * Get formulation names by keyword
     * @param $keyword
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getFormulationNames(Request $formulationRequest)
    {
        $formulations = null;
        $responseJson = null;
        //$keyword = \Input::get('keyword');
        $keyword = $formulationRequest->get('formulations');

        try
        {
            //$brands = HospitalServiceFacade::getBrandNames($keyword);
            $formulations = $this->hospitalService->getFormulationNames($keyword);

            if(!empty($formulations))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::FORMULATION_LIST_SUCCESS));
                $responseJson->setCount(sizeof($formulations));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_FORMULATION_LIST_FOUND));
            }

            $responseJson->setObj($formulations);
            $responseJson->sendSuccessResponse();
        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::FORMULATION_LIST_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::FORMULATION_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
    }

    //Lab Tests
    /**
     * Get all lab tests
     * @param none
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getLabTests(Request $labRequest)
    {
        $labTests = null;
        $responseJson = null;

        try
        {
            //$labTests = HospitalServiceFacade::getLabTests();
            $keyword = $labRequest->get('labtests');

            $labTests = $this->hospitalService->getLabTests($keyword);

            if(!empty($labTests))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::LAB_LIST_SUCCESS));
                $responseJson->setCount(sizeof($labTests));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::LAB_LIST_ERROR));
            }

            $responseJson->setObj($labTests);
            $responseJson->sendSuccessResponse();

        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LAB_LIST_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LAB_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
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
        $responseJson = null;
        //$jsonResponse = null;
        //dd('Inside prescriptions');
        try
        {
            //$patientLabTests = HospitalServiceFacade::getLabTestsForPatient($hospitalId, $doctorId);
            $patientLabTests = $this->hospitalService->getLabTestsForPatient($hospitalId, $doctorId);

            if(!empty($patientLabTests))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::LAB_LIST_SUCCESS));
                $responseJson->setCount(sizeof($patientLabTests));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_LABTEST_FOUND));
            }

            $responseJson->setObj($patientLabTests);
            $responseJson->sendSuccessResponse();
        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LAB_LIST_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LAB_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
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
        $responseJson = null;
        //dd('Inside prescriptions');
        try
        {
            //$patientLabTests = HospitalServiceFacade::getLabTestsByPatient($patientId);
            $patientLabTests = $this->hospitalService->getLabTestsByPatient($patientId);

            if(!empty($patientLabTests))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::LAB_LIST_SUCCESS));
                $responseJson->setCount(sizeof($patientLabTests));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_LABTEST_FOUND));
            }

            $responseJson->setObj($patientLabTests);
            $responseJson->sendSuccessResponse();
            //dd($jsonResponse);
        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LAB_LIST_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LAB_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
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
        $responseJson = null;
        //dd('Inside labtest details');

        try
        {
            //$labTestDetails = HospitalServiceFacade::getLabTestDetails($labTestId);
            $labTestDetails = $this->hospitalService->getLabTestDetails($labTestId);
            //dd($labTestDetails);

            if(!empty($labTestDetails))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::LAB_DETAILS_SUCCESS));
                $responseJson->setCount(sizeof($labTestDetails));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::NO_LAB_DETAILS_FOUND));
            }

            $responseJson->setObj($labTestDetails);
            $responseJson->sendSuccessResponse();
            //dd($jsonResponse);
        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LAB_DETAILS_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LAB_DETAILS_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
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
    //public function saveNewAppointment(Request $appointmentRequest)
    {
        $appointmentsVM = null;
        $status = true;
        $responseJson = null;
        try
        {
            $appointmentsVM = PatientProfileMapper::setPatientAppointment($appointmentRequest);
            //$status = HospitalServiceFacade::saveNewAppointment($appointmentsVM);
            $status = $this->hospitalService->saveNewAppointment($appointmentsVM);

            if($status)
            {
                //$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_PROFILE_SAVE_SUCCESS));
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_NEW_APPOINTMENT_SUCCESS));
                $responseJson->sendSuccessResponse();
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_NEW_APPOINTMENT_ERROR));
                $responseJson->sendSuccessResponse();
            }
        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.$hospitalExc->getUserErrorCode()));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_NEW_APPOINTMENT_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
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
        $responseJson = null;
        //$string = ""

        try
        {
            $patientLabTestVM = PatientPrescriptionMapper::setLabTestDetails($patientLabTestRequest);
            //$status = HospitalServiceFacade::savePatientLabTests($patientLabTestVM);
            $status = $this->hospitalService->savePatientLabTests($patientLabTestVM);

            if($status)
            {
                //$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_PROFILE_SAVE_SUCCESS));
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::LABTESTS_DETAILS_SAVE_SUCCESS));
                $responseJson->sendSuccessResponse();
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LABTESTS_DETAILS_SAVE_ERROR));
                $responseJson->sendSuccessResponse();
            }

        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LABTESTS_DETAILS_SAVE_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
            //return $jsonResponse;
        }
        catch(Exception $exc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::LABTESTS_DETAILS_SAVE_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
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

                        $hospitalInfo = HospitalServiceFacade::getProfile(Auth::user()->id);

                        Session::put('LoginHospitalDetails', $hospitalInfo[0]->hospital_name.' '.$hospitalInfo[0]->address);

                        //$hospitalId = HospitalServiceFacade::getHospitalId(UserType::USERTYPE_DOCTOR, $doctorid);

                        return redirect('fronthospital/'.Auth::user()->id.'/dashboard');
                    }
                    else if( Auth::user()->hasRole('doctor')  && (Auth::user()->delete_status==1) )
                    {
                        //Auth::logout();
                        //Session::flush();
                        //$msg="Login Details Incorrect! Please try Again. OR Missing Hospital Details";
                        //return redirect('/doctor/login')->with('message',$msg);

                        //dd('ISSUES');

                        $LoginUserType=Session::put('LoginUserType', 'doctor');

                        $doctorid = Auth::user()->id;
                        //dd($doctorid);

                        $hospitalsInfo = HospitalServiceFacade::getHospitalsByDoctorId($doctorid);
                        //dd($hospitalsInfo);
                        Session::put('LoginUserHospitals',$hospitalsInfo);

                        Session::put('LoginUserHospital', '');
                        Session::put('LoginHospitalDetails', '');
                        /*
                        $hospitalId = HospitalServiceFacade::getHospitalId(UserType::USERTYPE_DOCTOR, $doctorid);
                        //dd($hospitalId);
                        Session::put('LoginUserHospital', $hospitalId[0]->hospital_id);

                        $hospitalInfo = HospitalServiceFacade::getProfile($hospitalId[0]->hospital_id);
                        //dd($hospitalInfo);
                        Session::put('LoginHospitalDetails', $hospitalInfo[0]->hospital_name.' '.$hospitalInfo[0]->address);
                        */
                        $doctorInfo = HospitalServiceFacade::getDoctorDetails($doctorid);
                        //dd($doctorInfo);
                        Session::put('LoginDoctorDetails', $doctorInfo[0]->doctorDetails);
//dd('CIU');
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
                    /*else if(Auth::user()->hasRole('fronthospital'))
                    {
                        $LoginUserType=Session::put('LoginUserType', 'admin');
                        return redirect('admin/'.Auth::user()->id.'/dashboard');
                    }*/
                    else
                    {
                        Auth::logout();
                        Session::flush();
                        $msg="Login Details Incorrect! Please try Again.";
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

    public function getPatientsByHospitalForFront($hospitalId, Request $patientRequest)
    {
        //dd('HI');
        $patients = null;
        $keyword = $patientRequest->get('keyword');

        try
        {
            $patients = HospitalServiceFacade::getPatientsByHospital($hospitalId, $keyword);
            //dd($patients);
            //return json_encode('test');

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
    //public function saveAppointmentByHospitalForFront(NewAppointmentRequest $appointmentRequest)
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
                return redirect('fronthospital/rest/api/'.Auth::user()->id.'/patient/'.$appointmentRequest->patientId.'/completeappointment')->with('success',$msg);
            }
            else
            {
                $msg = "Patient Appointment Details Invalid / Incorrect! Try Again.";
                return redirect('fronthospital/rest/api/'.Auth::user()->id.'/patient/'.$appointmentRequest->patientId.'/completeappointment')->with('message',$msg);
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

    public function completeAppointmentByHospitalForFront($hospitalId,$patientId)
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

        return view('portal.hospital-completeappointment',compact('doctors','patientProfile'));
    }

    public function getPatientsByDoctorForFront($doctorId, $hospitalId, Request $doctorRequest)
    {
        //dd('HI');
        $patients = null;
        //$keyword = $doctorRequest->get('keyword');

        try
        {
            //$hospitalInfo = HospitalDoctor::where('doctor_id','=',$doctorId)->first();
            //$hospitalId=$hospitalInfo['hospital_id'];

            //dd($hospitalId);
            $patients = HospitalServiceFacade::getPatientsByHospitalAndDoctor($hospitalId, $doctorId);

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
        //dd($patientId);

        try
        {
            $appointments = HospitalServiceFacade::getPatientAppointments($patientId);
            //dd($appointments);
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

        return $appointments;
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
                $m->from('info@daiwiksoft.com', 'Learning Laravel');
                //$m->to('baskar2271@yahoo.com')->subject('Learning laravel test mail');
                $m->to('baskar2271@yahoo.com')->subject('Learning laravel test mail');
            });
        }
        catch(Exception $exc)
        {
            return response()->json(['message' => $exc->getMessage()]);
        }

        return response()->json(['message' => 'Request completed']);
    }

    /**
     * Get the doctor names for the hospital
     * @param $hospitalId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getDoctorNames($hospitalId, Request $nameRequest)
    {
        $doctorNames = null;
        $responseJson = null;
        //dd('HI');
        $keyword = $nameRequest->get('keyword');
        //dd($keyword);
        //return $keyword;
        try
        {
            //$patientNames = HospitalServiceFacade::searchPatientByName($keyword);
            $doctorNames = $this->hospitalService->getDoctorNames($hospitalId, $keyword);
            //dd($doctorNames);

            if(!empty($doctorNames))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::DOCTOR_NAME_SUCCESS));
                $responseJson->setCount(sizeof($doctorNames));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::HOSPITAL_NO_DOCTORS_FOUND));
            }

            $responseJson->setObj($doctorNames);
            $responseJson->sendSuccessResponse();
            //dd($jsonResponse);
        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::HOSPITAL_NO_DOCTORS_FOUND));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::HOSPITAL_NO_DOCTORS_FOUND));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
    }

    /**
     * Get patient names by keyword
     * @param $keyword
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientNames($hospitalId, Request $nameRequest)
    {
        $patientNames = null;
        $responseJson = null;

        $keyword = $nameRequest->get('keyword');
        //dd($keyword);
        //return $keyword;
        try
        {
            //$patientNames = HospitalServiceFacade::searchPatientByName($keyword);
            $patientNames = $this->hospitalService->getPatientNames($hospitalId, $keyword);
            //dd($patientNames);

            if(!empty($patientNames))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_LIST_SUCCESS));
                $responseJson->setCount(sizeof($patientNames));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_LIST_ERROR));
            }

            $responseJson->setObj($patientNames);
            $responseJson->sendSuccessResponse();
            //dd($jsonResponse);
        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_LIST_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
    }

    public function PatientDetailsByHospitalForFront($hid,$patientId)
    {
        $patientDetails = null;
        $patientPrescriptions = null;
        $labTests = null;
        //$jsonResponse = null;
        //dd('Inside patient details');
        try
        {
            //$patientDetails = HospitalServiceFacade::getPatientDetailsById($patientId);
            $patientDetails = HospitalServiceFacade::getPatientProfile($patientId);
            $patientPrescriptions = HospitalServiceFacade::getPrescriptionByPatient($patientId);
            $labTests = HospitalServiceFacade::getLabTestsByPatient($patientId);
            //$patientAppointment = HospitalServiceFacade::getPatientAppointments($patientId);
            $patientAppointment = HospitalServiceFacade::getPatientAppointmentsByHospital($patientId, $hid);
            //dd($patientAppointment);
        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
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

        return view('portal.hospital-patient-details',compact('patientDetails','patientPrescriptions','labTests','patientAppointment'));

    }

    public function PatientEditByHospitalForFront($hid,$patientId)
    {
        $patientDetails = null;
        $patientPrescriptions = null;
        $labTests = null;
        //$jsonResponse = null;
        //dd('Inside patient details');
        try
        {
            //$patientDetails = HospitalServiceFacade::getPatientDetailsById($patientId);
            $patientDetails = HospitalServiceFacade::getPatientProfile($patientId);
            //$patientPrescriptions = HospitalServiceFacade::getPrescriptionByPatient($patientId);
            //$labTests = HospitalServiceFacade::getLabTestsByPatient($patientId);
            //$patientAppointment = HospitalServiceFacade::getPatientAppointments($patientId);
            //dd($patientDetails);
        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
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

        return view('portal.hospital-patient-edit',compact('patientDetails'));

    }

    public function updatePatientsByHospitalForFront(Request $patientProfileRequest)
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

                $msg = "Patient Profile Updated Successfully.";
                return redirect('fronthospital/rest/api/'.Auth::user()->id.'/patients')->with('success',$msg);
            }
            else
            {
                $msg = "Patient Details Invalid / Incorrect! Try Again.";
                return redirect('fronthospital/rest/api/'.Auth::user()->id.'/patients')->with('message',$msg);
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
        $responseJson = null;
        //dd($doctorId);

        try
        {
            $feeReceipts = $this->hospitalService->getFeeReceipts($hospitalId, $doctorId);
            //dd($patientNames);

            if(!empty($feeReceipts))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::FEE_RECEIPT_LIST_SUCCESS));
                $responseJson->setCount(sizeof($feeReceipts));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_FEE_RECEIPT_LIST));
            }

            $responseJson->setObj($feeReceipts);
            $responseJson->sendSuccessResponse();
        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.$hospitalExc->getUserErrorCode()));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::FEE_RECEIPT_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
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
        $responseJson = null;
        //dd($doctorId);

        try
        {
            $feeReceipts = $this->hospitalService->getFeeReceiptsByPatient($patientId);
            //dd($patientNames);

            if(!empty($feeReceipts))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::FEE_RECEIPT_LIST_SUCCESS));
                $responseJson->setCount(sizeof($feeReceipts));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::FEE_RECEIPT_LIST_ERROR));
            }

            $responseJson->setObj($feeReceipts);
            $responseJson->sendSuccessResponse();
        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.$hospitalExc->getUserErrorCode()));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::FEE_RECEIPT_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
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
        $responseJson = null;
        //dd($receiptId);

        try
        {
            $feeReceiptDetails = $this->hospitalService->getReceiptDetails($receiptId);
            //dd($patientNames);

            if(!is_null($feeReceiptDetails) && !empty($feeReceiptDetails))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::FEE_RECEIPT_DETAILS_SUCCESS));
                $responseJson->setCount(sizeof($feeReceiptDetails));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_FEE_RECEIPT_DETAILS_FOUND));
            }

            $responseJson->setObj($feeReceiptDetails);
            $responseJson->sendSuccessResponse();
        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::FEE_RECEIPT_DETAILS_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::FEE_RECEIPT_DETAILS_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
    }

    /**
     * Save fee receipt
     * @param $feeReceiptRequest
     * @throws $hospitalException
     * @return true | false
     * @author Baskar
     */

    public function saveFeeReceipt(FeeReceiptRequest $feeReceiptRequest)
    {
        $feeReceiptVM = null;
        $status = true;
        $responseJson = null;

        try
        {
            $feeReceiptVM = PatientProfileMapper::setFeeReceipt($feeReceiptRequest);
            $status = $this->hospitalService->saveFeeReceipt($feeReceiptVM);

            if($status)
            {
                //$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_PROFILE_SAVE_SUCCESS));
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::FEE_RECEIPT_SAVE_SUCCESS));
                $responseJson->sendSuccessResponse();
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::FEE_RECEIPT_SAVE_ERROR));
                $responseJson->sendSuccessResponse();
            }
        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.$hospitalExc->getUserErrorCode()));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::FEE_RECEIPT_SAVE_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
    }

    /**
     * Forward fee receipt details by SMS
     * @param $receiptId, $mobile
     * @throws HospitalException
     * @return array | null
     * @author Baskar
     */

    public function forwardFeeReceiptBySMS($receiptId, $mobile, Request $request)
    {
        //dd($request['mobile']);
        $mobile=$request['mobile'];
        $feeReceiptDetails = null;
        $responseJson = null;
        $status = true;

        try
        {
            $feeReceiptDetails = $this->hospitalService->getReceiptDetails($receiptId);
            //dd($feeReceiptDetails);

            if(!is_null($feeReceiptDetails) && !empty($feeReceiptDetails))
            {
                $status = $this->sendFeeReceiptAsSMS($feeReceiptDetails, $mobile);
                $msg = "Message Sent Successfully";
            }

        }
        catch(HospitalException $hospitalExc)
        {
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_PROFILE_SAVE_ERROR));
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
            //return $jsonResponse;
            return redirect('fronthospital/rest/api/receipt/'.$receiptId.'/details')->with('message',$msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SAVE_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
            return redirect('fronthospital/rest/api/receipt/'.$receiptId.'/details')->with('message',$msg);
        }

        //return $responseJson;
        return redirect('fronthospital/rest/api/receipt/'.$receiptId.'/details')->with('success',$msg);
    }

    /**
     * Forward fee receipt details by Email
     * @param $receiptId, $mail
     * @throws HospitalException
     * @return array | null
     * @author Baskar
     */

    public function forwardFeeReceiptApiByMail($receiptId, $email)
    {
        $feeReceiptDetails = null;
        $responseJson = null;
        $status = true;

        try
        {
            $feeReceiptDetails = $this->hospitalService->getReceiptDetails($receiptId);
            //dd($feeReceiptDetails);

            if(!is_null($feeReceiptDetails) && !empty($feeReceiptDetails))
            {
                $subject = "Fee Receipt";
                $name = "ePrescription and Lab Tests Application";
                $title = "Fee Receipt";
                //$content = $prescriptionDetails;
                $to = $email;
                $data = array('name' => $name, 'title' => $title, 'feeReceiptDetails' => $feeReceiptDetails);


                Mail::send('emails.hospital-fee', $data, function ($m) use($to, $subject){
                    //$m->from('prescriptionapp1@gmail.com', $name);
                    //$m->to($to)->subject($subject);
                    $m->from('prescriptionapp1@gmail.com', 'ePrescription and Lab Tests Application');;
                    //$m->to('alagirivimal@gmail.com')->subject('ePrescription and Lab Tests Application');
                    $m->to($to)->subject($subject);
                });

                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::FEERECEIPT_EMAIL_SUCCESS));
                //$responseJson->setObj("Mail Sent Successfully");
                $responseJson->sendSuccessResponse();

                //dd($responseJson);
            }

        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::FEERECEIPT_EMAIL_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::FEERECEIPT_EMAIL_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
        //return view('portal.patient-labtest-details',compact('prescriptionDetails'));
    }

    /**
     * Forward fee receipt details by Email
     * @param $receiptId, $mail
     * @throws HospitalException
     * @return array | null
     * @author Baskar
     */

    public function forwardFeeReceiptByMail($receiptId, $email, Request $request)
    {
        //dd($request['email']);
        $email=$request['email'];
        $feeReceiptDetails = null;
        $responseJson = null;
        $status = true;
        $msg = null;

        try
        {
            $feeReceiptDetails = $this->hospitalService->getReceiptDetails($receiptId);
            //dd($feeReceiptDetails);

            if(!is_null($feeReceiptDetails) && !empty($feeReceiptDetails))
            {
                $subject = "Fee Receipt";
                $name = "ePrescription and Lab Tests Application";
                $title = "Fee Receipt";
                //$content = $prescriptionDetails;
                $to = $email;
                $data = array('name' => $name, 'title' => $title, 'feeReceiptDetails' => $feeReceiptDetails);

                Mail::send('emails.hospital-fee', $data, function ($m) use($to, $subject){
                    //$m->from('prescriptionapp1@gmail.com', $name);
                    //$m->to($to)->subject($subject);
                    $m->from('prescriptionapp1@gmail.com', 'ePrescription and Lab Tests Application');;
                    //$m->to('alagirivimal@gmail.com')->subject('ePrescription and Lab Tests Application');
                    $m->to($to)->subject($subject);
                });
                $msg = "Message Sent Successfully";
                //dd("EMail Sent");
                //dd($feeReceiptDetails);
            }

        }
        catch(HospitalException $hospitalExc)
        {
            $errorMsg = $hospitalExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($hospitalExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }


        return redirect('fronthospital/rest/api/receipt/'.$receiptId.'/details')->with('success',$msg);
        //return view('portal.hospital-fee-details');

        //return $responseJson;
        //return redirect('fronthospital/rest/api/'.Auth::user()->id.'/addpatient')->with('message',$msg);
    }

    /**
     * Forward fee receipt details by SMS
     * @param $receiptId, $mobile
     * @throws HospitalException
     * @return array | null
     * @author Baskar
     */

    public function forwardFeeReceiptApiBySMS($receiptId, $mobile)
    {
        $feeReceiptDetails = null;
        $responseJson = null;
        $status = true;

        try
        {
            $feeReceiptDetails = $this->hospitalService->getReceiptDetails($receiptId);
            //dd($feeReceiptDetails);

            if(!is_null($feeReceiptDetails) && !empty($feeReceiptDetails))
            {
                $status = $this->sendFeeReceiptAsSMS($feeReceiptDetails, $mobile);
            }

            if($status)
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::FEERECEIPT_SMS_SUCCESS));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::FEERECEIPT_SMS_ERROR));
            }

            //$responseJson->setObj($response->getStatusCode());
            $responseJson->sendSuccessResponse();
        }
        catch(HospitalException $pharmacyExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::FEE_RECEIPT_DETAILS_ERROR));
            $responseJson->sendErrorResponse($pharmacyExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::FEE_RECEIPT_DETAILS_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
    }

    private function sendFeeReceiptAsSMS($feeReceiptDetails, $mobile)
    {
        $patientName = $feeReceiptDetails['patientDetails']->name;
        $doctorName = $feeReceiptDetails['doctorDetails']->name;
        $hospitalName = $feeReceiptDetails['hospitalDetails']->hospital_name;
        $doctorFee = $feeReceiptDetails['feeDetails']['fee'];
        //dd($doctorFee);
        $feeInWords = $feeReceiptDetails['feeDetails']['inWords'];
        $status = true;

        $message = "Patient Name : ".$patientName."%0a"
            ." Doctor Name: ".$doctorName."%0a"
            ." Hospital Name: ".$hospitalName."%0a"
            ." Received Fee: ".$doctorFee." (In Words ".$feeInWords.") with thanks towards doctor consultation charges"."%0a";

        $client = new Client();
        $response = $client->get('http://bhashsms.com/api/sendmsg.php?user=Daiwiksoft&pass=Daiwik2612&sender=daiwik&phone='.$mobile.'&text='.$message.'&priority=ndnd&stype=normal');

        if($response->getStatusCode() != 200)
        {
            $status = false;
        }

        return $status;
    }


    public function getDoctorsForFront($hospitalId, Request $nameRequest)
    {
        $doctorNames = null;
        $responseJson = null;
        //dd('HI');
        $keyword = $nameRequest->get('keyword');
        //dd($keyword);
        //return $keyword;
        try
        {
            //$patientNames = HospitalServiceFacade::searchPatientByName($keyword);
            $doctors = $this->hospitalService->getDoctorNames($hospitalId, $keyword);
            //dd($doctors);

        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::HOSPITAL_NO_DOCTORS_FOUND));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::HOSPITAL_NO_DOCTORS_FOUND));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        //return $responseJson;
        return view('portal.hospital-doctors',compact('doctors'));
    }


    public function getFeeReceiptsForFront($hospitalId, $doctorId)
    {
        $feeReceipts = null;
        $responseJson = null;
        //dd($doctorId);

        try
        {
            $feeReceipts = $this->hospitalService->getFeeReceipts($hospitalId, $doctorId);
            //dd($feeReceipts);
            /*
            if(!empty($feeReceipts))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::FEE_RECEIPT_LIST_SUCCESS));
                $responseJson->setCount(sizeof($feeReceipts));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::FEE_RECEIPT_LIST_ERROR));
            }

            $responseJson->setObj($feeReceipts);
            $responseJson->sendSuccessResponse();
            */
        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
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

        return view('portal.hospital-doctor-fees',compact('feeReceipts'));
        //return $responseJson;
    }

    public function getReceiptDetailsForFront($receiptId)
    {
        $feeReceiptDetails = null;
        $responseJson = null;
        //dd($receiptId);

        try
        {
            $feeReceiptDetails = $this->hospitalService->getReceiptDetails($receiptId);

        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
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

        return view('portal.hospital-fee-details',compact('feeReceiptDetails','receiptId'));
        //return $responseJson;
    }

    public function addFeeReceiptForFront($hospitalId)
    {
        //dd('HI');
        $feeReceiptDetails = null;
        $responseJson = null;
        //dd($hospitalId);
        $keyword = null;

        try
        {

            $patients = $this->hospitalService->getPatientsByHospital($hospitalId, $keyword);
            //dd($patients);

            $doctors = $this->hospitalService->getDoctorsByHospitalId($hospitalId);
            //dd($doctors);
            //$feeReceiptDetails = $this->hospitalService->getReceiptDetails($receiptId);
            //dd($feeReceiptDetails);

        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
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

        return view('portal.hospital-fee-add',compact('patients','doctors'));
        //return $responseJson;
    }



    public function saveFeeReceiptForFront(FeeReceiptRequest $feeReceiptRequest)
    {
        $feeReceiptVM = null;
        $status = true;
        $responseJson = null;

        try
        {
            $feeReceiptVM = PatientProfileMapper::setFeeReceipt($feeReceiptRequest);
            $status = $this->hospitalService->saveFeeReceipt($feeReceiptVM);
            //dd($status);

            if($status)
            {
                $msg=trans('messages.'.ErrorEnum::PATIENT_PROFILE_SAVE_SUCCESS);

            }
            else
            {
                $msg=trans('messages.'.ErrorEnum::FEE_RECEIPT_SAVE_ERROR);
                return redirect('fronthospital/rest/api/'.Auth::user()->id.'/addfeereceipt')->with('message',$msg);
            }


        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
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

        return redirect('fronthospital/rest/api/'.Auth::user()->id.'/addfeereceipt')->with('success',$msg);

        //return $responseJson;

    }


    public function onlinePayment()
    {

        try
        {


        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
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

        return view('portal.hospital-online-payment');
        //return $responseJson;
    }

    public function processPayment(Request $paymentRequest)
    {
       // dd($paymentRequest->all());

        $parameters = $paymentRequest->all();
        /*
        $parameters = [

            'tid' => '45646489556322',
            'order_id' => '1232212',
            'amount' => '1200.00',
            'firstname' => 'Baskaran',
            'email' => 'baskar2271@yahoo.com',
            'phone' => '9988844455',
            'productinfo' => 'test',
        ];
        */
        //dd($parameters);
        try
        {
            //$order = Indipay::prepare($parameters);
            $order = Indipay::gateway('PayUMoney')->prepare($parameters);
            //dd($order);
            return Indipay::process($order);
            //dd($order);


        }
        catch(Exception $exc)
        {
            //dd($exc);
        }



        //return Indipay::process($order);

    }


    public function successPayment(Request $request)
    {

        try
        {

            // For default Gateway
            $response = Indipay::response($request);

            // For Otherthan Default Gateway
            $response = Indipay::gateway('NameOfGatewayUsedDuringRequest')->response($request);

        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
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

        return view('portal.hospital-online-payment-success',compact('response'));
        //return $responseJson;
    }


    public function failurePayment(Request $request)
    {

        try
        {

            // For default Gateway
            $response = Indipay::response($request);

            // For Otherthan Default Gateway
            $response = Indipay::gateway('NameOfGatewayUsedDuringRequest')->response($request);

        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
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

        //dd($response);
        return view('portal.hospital-online-payment-failure',compact('response'));
        //return $responseJson;
    }


    public function changeHospital(Request $loginRequest)
    {
        $loginInfo = $loginRequest->all();
        //dd($loginInfo['hospital']);
        //$userSession = null;

        try
        {
            if (!empty($loginInfo))
            {

                $hospital_id = $loginInfo['hospital'];
                //dd($hospital_id);
                Session::put('LoginUserHospital', $hospital_id);

                $hospitalInfo = HospitalServiceFacade::getProfile($hospital_id);
                //dd($hospitalInfo);
                Session::put('LoginHospitalDetails', $hospitalInfo[0]->hospital_name.' '.$hospitalInfo[0]->address);

                return redirect('doctor/'.Auth::user()->id.'/dashboard');

            }
            else
            {
                //$prescriptionResult = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::DOCTOR_LOGIN_FAILURE));
                $msg = "Please Choose Hospital Details.";
                return redirect('doctor/'.Auth::user()->id.'/dashboard')->with('message',$msg);

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
}
