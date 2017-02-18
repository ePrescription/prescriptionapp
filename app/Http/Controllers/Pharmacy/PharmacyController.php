<?php

namespace App\Http\Controllers\Pharmacy;

use App\prescription\mapper\PharmacyMapper;
use App\prescription\services\HospitalService;
use App\prescription\services\PharmacyService;
use App\prescription\services\HelperService;
use App\prescription\utilities\Exception\HospitalException;
use App\prescription\utilities\Exception\PharmacyException;
use App\prescription\utilities\Exception\AppendMessage;
use App\prescription\common\ResponseJson;
use App\prescription\utilities\ErrorEnum\ErrorEnum;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Exception;
use Log;

use Mail;

class PharmacyController extends Controller
{
    protected $pharmacyService;

    public function __construct(PharmacyService $pharmacyService)
    {
        $this->pharmacyService = $pharmacyService;
    }

    /**
     * Get the profile of the pharmacy
     * @param $pharmacyId
     * @throws $pharmacyException
     * @return array | null
     * @author Baskar
     */

    public function getProfile($pharmacyId)
    {
        $pharmacyProfile = null;
        //dd('Inside get profile function in pharmacy controller');

        try
        {
            $pharmacyProfile = $this->pharmacyService->getProfile($pharmacyId);
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

        return view('portal.pharmacy-profile',compact('pharmacyProfile'));

        //return $pharmacyProfile;
    }


    /**
     * Get the profile of the pharmacy
     * @param $pharmacyId
     * @throws $pharmacyException
     * @return array | null
     * @author Baskar
     */

    public function editProfile($pharmacyId, HelperService $helperService)
    {
        $pharmacyProfile = null;
        //dd('Inside get profile function in pharmacy controller');

        try
        {
            $pharmacyProfile = $this->pharmacyService->getProfile($pharmacyId);
            $cities = $helperService->getCities();
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

        return view('portal.pharmacy-editprofile',compact('pharmacyProfile','cities'));

        //return $pharmacyProfile;
    }


    /**
     * Get the profile of the pharmacy
     * @param $pharmacyId
     * @throws $pharmacyException
     * @return array | null
     * @author Baskar
     */

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

        return view('portal.pharmacy-changepassword');

        //return $pharmacyProfile;
    }

    /**
     * Get the list of patients for the selected pharmacy
     * @param $pharmacyId, $hospitalId
     * @throws $pharmacyException
     * @return array | null
     * @author Baskar
     */

    public function getPatientListForPharmacy($pharmacyId, $hospitalId)
    {
        $patients = null;

        try
        {
            $patients = $this->pharmacyService->getPatientListForPharmacy($pharmacyId, $hospitalId);
            //dd($patients);
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

        return view('portal.pharmacy-patients',compact('patients'));

        //return $patients;
    }

    /**
     * Get the list of prescriptions for the selected pharmacy
     * @param $pharmacyId, $hospitalId
     * @throws $pharmacyException
     * @return array | null
     * @author Baskar
     */

    public function getPrescriptionListForPharmacy($pharmacyId, $hospitalId)
    {
        $prescriptions = null;

        try
        {
            $prescriptions = $this->pharmacyService->getPrescriptionListForPharmacy($pharmacyId, $hospitalId);
            //dd($patients);
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

        return view('portal.pharmacy-prescriptions',compact('prescriptions'));

        //return $prescriptions;
    }

    /**
     * Get prescription details for the prescription id
     * @param $prescriptionId
     * @throws $pharmacyException
     * @return array | null
     * @author Baskar
     */

    public function getPrescriptionDetails(HospitalService $hospitalService, $prescriptionId)
    {
        $prescriptionDetails = null;
        //dd('Inside prescription details');

        try
        {
            $prescriptionDetails = $hospitalService->getPrescriptionDetails($prescriptionId);
            //dd($prescriptionDetails);

        }
        catch(PharmacyException $pharmacyExc)
        {
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $errorMsg = $pharmacyExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($pharmacyExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.pharmacy-prescription-details',compact('prescriptionDetails'));

        //return $prescriptionDetails;
    }

    /**
     * Get prescription list by PRID
     * @param $prid
     * @throws $pharmacyException
     * @return array | null
     * @author Baskar
     */

    public function getPrescriptionByPrid(Request $pridRequest)
    {
        $prescriptions = null;
        //dd('Inside prescription by prid');
        $prId = $pridRequest->get('prid');

        try
        {
            $prescriptions = $this->pharmacyService->getPrescriptionByPrid($prId);
            //dd($prescription);

        }
        catch(PharmacyException $pharmacyExc)
        {
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $errorMsg = $pharmacyExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($pharmacyExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.pharmacy-prescriptions',compact('prescriptions'));

        //return $prescription;
    }

    /**
     * Edit Pharmacy Details
     * @param $pharmacyRequest
     * @throws $pharmacyException
     * @return true | false
     * @author Baskar
     */

    public function editPharmacy(Request $pharmacyRequest)
    {
        $pharmacyVM = null;
        $status = true;
        //$string = ""
        //dd($pharmacyRequest);
        try
        {
            /*
            //dd('Inside edit pharmacy');
            $pharmacy = array('pharmacy_name' => 'MedPlus', 'address' => 'test', 'city' => 15, 'country' => 99,
                'telephone' => '5464645654', 'email' => 'medplys@gmail.com');
            //$pharmacyVM = PharmacyMapper::setPhamarcyDetails($pharmacyRequest);
            */
            $pharmacyVM = PharmacyMapper::setPhamarcyDetails($pharmacyRequest);
            //dd($pharmacyVM);
            $status = $this->pharmacyService->editPharmacy($pharmacyVM);
            //dd($status);

            /*if($status)
            {
                //$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SAVE_SUCCESS));
            }*/

            if($status) {
                $pharmacyId=$pharmacyVM->getPharmacyId();
                //dd($pharmacyId);
                $pharmacyProfile = $this->pharmacyService->getProfile($pharmacyId);
                $message= "Profile Details Updated Successfully";
            }

        }
        catch(PharmacyException $profileExc)
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

       // dd($pharmacyProfile);
        return view('portal.pharmacy-profile',compact('pharmacyProfile','message'));

        //return $jsonResponse;
    }


    /**
     * Get the list of prescriptions for the selected patient
     * @param $patientId
     * @throws $pharmacyException
     * @return array | null
     * @author Vimal
     */


    public function getPrescriptionListForPatient($patientId)
    {
        $prescriptions = null;

        try
        {
            $prescriptions = $this->pharmacyService->getPrescriptionListForPatient($patientId);
            //dd($patients);
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

        return view('portal.patient-prescriptions',compact('prescriptions'));

        //return $prescriptions;
    }


    /**
     * Get prescription list by PRID
     * @param $prid
     * @throws $pharmacyException
     * @return array | null
     * @author Vimal
     */

    public function getPrescriptionByPridForPatient(Request $pridRequest)
    {
        $prescriptions = null;
        //dd('Inside prescription by prid');
        $prId = $pridRequest->get('prid');

        try
        {
            $prescriptions = $this->pharmacyService->getPrescriptionByPrid($prId);
            //dd($prescription);

        }
        catch(PharmacyException $pharmacyExc)
        {
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $errorMsg = $pharmacyExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($pharmacyExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.patient-prescriptions',compact('prescriptions'));

        //return $prescription;
    }

    /**
     * Get prescription details for the prescription id
     * @param $prescriptionId
     * @throws $pharmacyException
     * @return array | null
     * @author Vimal
     */

    public function getPrescriptionDetailsForPatient(HospitalService $hospitalService, $prescriptionId)
    {
        $prescriptionDetails = null;
        //dd('Inside prescription details');

        try
        {
            $prescriptionDetails = $hospitalService->getPrescriptionDetails($prescriptionId);
            //dd($prescriptionDetails);

        }
        catch(PharmacyException $pharmacyExc)
        {
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $errorMsg = $pharmacyExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($pharmacyExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.patient-prescription-details',compact('prescriptionDetails'));

        //return $prescriptionDetails;
    }

    /**
     * Forward the prescription details to the specified mail id
     * @param $prescriptionId, $email
     * @throws PharmacyException
     * @return array | null
     * @author Baskar
     */


    //public function forwardPrescriptionDetailsByMail(HospitalService $hospitalService, $prescriptionId, Request $emailRequest)
    public function forwardPrescriptionDetailsByMail(HospitalService $hospitalService, $prescriptionId, $email)
    {
        //dd($prescriptionId.' '.$email);

        //return $prescriptionId;
        $prescriptionDetails = null;
        $prescriptionMailInfo = null;
        //dd('Inside prescription details');

        try
        {
            $prescriptionDetails = $hospitalService->getPrescriptionDetails($prescriptionId);
            dd($prescriptionDetails);

            $subject = "Prescription Details";
            $name = "ePrescription and Lab Tests Application";
            $title = "Prescription Details";
            $content = $prescriptionDetails;
            $to = $email;
            //$to = $emailRequest->email;

            //return json_encode($to);


            $data = array('name' => $name, 'title' => $title, 'prescriptionDetails' => $prescriptionDetails);

            Mail::send('emails.prescription', $data, function ($m) use($to, $subject){
                //$m->from('prescriptionapp1@gmail.com', $name);
                //$m->to($to)->subject($subject);
                $m->from('info@daiwiksoft.com', 'ePrescription and Lab Tests Application');;
                //$m->to('alagirivimal@gmail.com')->subject('ePrescription and Lab Tests Application');
                $m->to($to)->subject($subject);
            });

            $prescriptionMailInfo = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SUCCESS));
            $prescriptionMailInfo->setObj("Mail Sent Successfully");

        }
        catch(PharmacyException $pharmacyExc)
        {
            //dd($pharmacyExc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $errorMsg = $pharmacyExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($pharmacyExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $prescriptionMailInfo;
        //return view('portal.patient-labtest-details',compact('prescriptionDetails'));
    }

    /**
     * Forward the prescription details by SMS
     * @param $prescriptionId, $email
     * @throws PharmacyException
     * @return array | null
     * @author Baskar
     */

    public function forwardPrescriptionDetailsBySMS(HospitalService $hospitalService, $prescriptionId, $mobile)
    {

        $prescriptionDetails = null;
        $prescriptionSMSInfo = null;
        //dd('Inside prescription details');

        try
        {
            //$prescriptionDetails = $hospitalService->getPrescriptionDetails($prescriptionId);
            //dd($labTestDetails);
            $prescriptionSMSInfo = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SUCCESS));
            $prescriptionSMSInfo->setObj("SMS Sent Successfully");

        }
        catch(PharmacyException $pharmacyExc)
        {
            //dd($pharmacyExc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $errorMsg = $pharmacyExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($pharmacyExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return $prescriptionSMSInfo;
        //return view('portal.patient-labtest-details',compact('prescriptionDetails'));
    }


    public function getPrescriptionListForDoctor($doctorId, $hospitalId)
    {
        $prescriptions = null;

        try
        {
            $prescriptions = $this->pharmacyService->getPrescriptionListForDoctor($doctorId, $hospitalId);
            //dd($patients);
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

        return view('portal.doctor-prescriptions',compact('prescriptions'));

        //return $prescriptions;
    }


    public function getPrescriptionDetailsForDoctor(HospitalService $hospitalService, $prescriptionId)
    {
        $prescriptionDetails = null;
        //dd('Inside prescription details');

        try
        {
            $prescriptionDetails = $hospitalService->getPrescriptionDetails($prescriptionId);
            //dd($prescriptionDetails);

        }
        catch(PharmacyException $pharmacyExc)
        {
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $errorMsg = $pharmacyExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($pharmacyExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.doctor-prescription-details',compact('prescriptionDetails'));

        //return $prescriptionDetails;
    }


    public function getPrescriptionDetailsForHospital(HospitalService $hospitalService, $prescriptionId)
    {
        $prescriptionDetails = null;
        //dd('Inside prescription details');

        try
        {
            $prescriptionDetails = $hospitalService->getPrescriptionDetails($prescriptionId);
            //dd($prescriptionDetails);

        }
        catch(PharmacyException $pharmacyExc)
        {
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $errorMsg = $pharmacyExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($pharmacyExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.hospital-prescription-details',compact('prescriptionDetails'));

        //return $prescriptionDetails;
    }

}
