<?php

namespace App\Http\Controllers\Lab;

use App\prescription\mapper\LabMapper;
use App\prescription\services\HelperService;
use App\prescription\services\HospitalService;
use App\prescription\services\LabService;
use App\prescription\utilities\Exception\LabException;
use App\prescription\utilities\Exception\AppendMessage;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Exception;
use Log;

class LabController extends Controller
{
    protected $labService;

    public function __construct(LabService $labService)
    {
        $this->labService = $labService;
    }

    /**
     * Get the profile of the lab
     * @param $labId
     * @throws $labException
     * @return array | null
     * @author Baskar
     */

    public function getProfile($labId)
    {
        $labProfile = null;
        //dd('Inside get profile function in lab controller');

        try
        {
            $labProfile = $this->labService->getProfile($labId);
            //dd($labProfile);
        }
        catch(LabException $profileExc)
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

        return view('portal.lab-profile',compact('labProfile'));

        //return $labProfile;
    }

    /**
     * Get the profile of the lab
     * @param $labId
     * @throws $labException
     * @return array | null
     * @author Vimal
     */

    public function editProfile($labId, HelperService $helperService)
    {
        $labProfile = null;
        $cities = null;
        //dd('Inside get profile function in lab controller');

        try
        {
            $labProfile = $this->labService->getProfile($labId);
            $cities = $helperService->getCities();
            //dd($cities);
        }
        catch(LabException $profileExc)
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

        return view('portal.lab-editprofile',compact('labProfile','cities'));

        //return $labProfile;
    }

    /**
     * Get the profile of the lab
     * @param $labId
     * @throws $labException
     * @return array | null
     * @author Vimal
     */

    public function editChangePassword($labId)
    {
        $labProfile = null;
        //dd('Inside get profile function in lab controller');

        try
        {
           // $labProfile = $this->labService->getProfile($labId);
            //dd($labProfile);
        }
        catch(LabException $profileExc)
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

        return view('portal.lab-changepassword');

        //return $labProfile;
    }

    /**
     * Get the list of patients for the selected lab
     * @param $labId, $hospitalId
     * @throws $labException
     * @return array | null
     * @author Baskar
     */

    public function getPatientListForLab($labId, $hospitalId)
    {
        $patients = null;

        try
        {
            $patients = $this->labService->getPatientListForLab($labId, $hospitalId);
            //dd($patients);
        }
        catch(LabException $profileExc)
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

        return view('portal.lab-patients',compact('patients'));

        //return $patients;
    }

    /**
     * Get the list of lab tests for the selected lab
     * @param $labId, $hospitalId
     * @throws $labException
     * @return array | null
     * @author Baskar
     */

    public function getTestsForLab($labId, $hospitalId)
    {
        $labTests = null;

        try
        {
            $labTests = $this->labService->getTestsForLab($labId, $hospitalId);
            //dd($labTests);
        }
        catch(LabException $profileExc)
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

        return view('portal.lab-labtest',compact('labTests'));

        //return $labTests;

    }

    /**
     * Get lab tests by LID
     * @param $lid
     * @throws $labException
     * @return array | null
     * @author Baskar
     */

    public function getLabTestsByLid(Request $lidRequest)
    {
        $labTests = null;
        //dd('Inside labtests by lid');
        $lid = $lidRequest->get('lid');
        //dd($lid);
        try
        {
            $labTests = $this->labService->getLabTestsByLid($lid);
            //dd($labTests);

        }
        catch(LabException $labExc)
        {
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $errorMsg = $labExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($labExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

       // return $labTests;

        return view('portal.lab-labtest',compact('labTests'));

        //return $labTests;
    }

    /**
     * Get lab test details for the given lab test id
     * @param $labTestId
     * @throws $labException
     * @return array | null
     * @author Baskar
     */

    public function getLabTestDetails(HospitalService $hospitalService, $labTestId)
    {
        $labTestDetails = null;
        //dd('Inside prescription details');

        try
        {
            $labTestDetails = $hospitalService->getLabTestDetails($labTestId);
            //dd($labTestDetails);

        }
        catch(LabException $labExc)
        {
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $errorMsg = $labExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($labExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.lab-labtest-details',compact('labTestDetails'));
    }

    /**
     * Edit Lab Details
     * @param $labRequest
     * @throws $labException
     * @return true | false
     * @author Baskar
     */

    public function editLab(Request $labRequest)
    {
        $labVM = null;
        $status = true;
        $message = null;
        //$string = ""

        try
        {
            //dd('Inside edit lab');
            /*
            $lab = array('lab_name' => 'Anderson Diagnostics', 'address' => 'test', 'city' => 15, 'country' => 99,
                'pincode' => '600005' , 'telephone' => '5464645654', 'email' => 'anderson@gmail.com');
            */
            //dd($labRequest);
            //$pharmacyVM = PharmacyMapper::setPhamarcyDetails($pharmacyRequest);
            $labVM = LabMapper::setLabDetails($labRequest);
            //dd($labVM);
            $status = $this->labService->editLab($labVM);
            //dd($status);

            /*if($status)
            {
                //$jsonResponse = new ResponseJson(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_SAVE_SUCCESS));
            }*/

            if($status) {
                $labId=$labVM->getLabId();
                $labProfile = $this->labService->getProfile($labId);
                $message= "Profile Details Updated Successfully";
            }
        }
        catch(LabException $profileExc)
        {
            //dd($profileExc);
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

        return view('portal.lab-profile',compact('labProfile','message'));

        //return $jsonResponse;
    }


    /**
     * Get the list of lab tests for the selected patient
     * @param $patientId
     * @throws $labException
     * @return array | null
     * @author Vimal
     */

    public function getTestsForLabForPatient($patientId)
    {
        $labTests = null;

        try
        {
            $labTests = $this->labService->getTestsForLabForPatient($patientId);
            //dd($labTests);
        }
        catch(LabException $profileExc)
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

        return view('portal.patient-labtest',compact('labTests'));

        //return $labTests;

    }

    /**
     * Get lab tests by LID
     * @param $lid
     * @throws $labException
     * @return array | null
     * @author Vimal
     */

    public function getLabTestsByLidForPatient(Request $lidRequest)
    {
        $labTests = null;
        //dd('Inside labtests by lid');
        $lid = $lidRequest->get('lid');
        //dd($lid);
        try
        {
            $labTests = $this->labService->getLabTestsByLid($lid);
            //dd($labTests);

        }
        catch(LabException $labExc)
        {
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $errorMsg = $labExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($labExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        // return $labTests;

        return view('portal.patient-labtest',compact('labTests'));

        //return $labTests;
    }


    /**
     * Get lab test details for the given lab test id
     * @param $labTestId
     * @throws $labException
     * @return array | null
     * @author Vimal
     */

    public function getLabTestDetailsForPatient(HospitalService $hospitalService, $labTestId)
    {
        $labTestDetails = null;
        //dd('Inside prescription details');

        try
        {
            $labTestDetails = $hospitalService->getLabTestDetails($labTestId);
            //dd($labTestDetails);

        }
        catch(LabException $labExc)
        {
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $errorMsg = $labExc->getMessageForCode();
            $msg = AppendMessage::appendMessage($labExc);
            Log::error($msg);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            //$jsonResponse = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PRESCRIPTION_DETAILS_ERROR));
            $msg = AppendMessage::appendGeneralException($exc);
            Log::error($msg);
        }

        return view('portal.patient-labtest-details',compact('labTestDetails'));
    }
}
