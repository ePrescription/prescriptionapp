<?php

namespace App\Http\Controllers\Doctor;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\prescription\services\HospitalService;
use App\prescription\common\ResponsePrescription;
use App\prescription\utilities\ErrorEnum\ErrorEnum;
use App\prescription\utilities\Exception\HospitalException;
use Exception;
use App\Http\Controllers\Controller;

class DoctorApiController extends Controller
{
    protected $hospitalService;

    public function __construct(HospitalService $hospitalService) {
        $this->hospitalService = $hospitalService;
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
        $responseJson = null;
        //dd('Inside doctor api controller');

        try
        {
            //dd($this->hospitalService);
            $mainSymptoms = $this->hospitalService->getMainSymptoms();

            if(!is_null($mainSymptoms) && !empty($mainSymptoms))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::MAIN_SYMPTOMS_LIST_SUCCESS));
                $responseJson->setCount(sizeof($mainSymptoms));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_MAIN_SYMPTOMS_LIST_FOUND));
            }

            $responseJson->setObj($mainSymptoms);
            $responseJson->sendSuccessResponse();
        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::MAIN_SYMPTOMS_LIST_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::MAIN_SYMPTOMS_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
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
        $responseJson = null;
        //dd($mainSymptomsId);

        try
        {
            $subSymptoms = $this->hospitalService->getSubSymptomsForMainSymptoms($mainSymptomsId);
            //dd($subSymptoms);

            if(!is_null($subSymptoms) && !empty($subSymptoms))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::SUB_SYMPTOMS_LIST_SUCCESS));
                $responseJson->setCount(sizeof($subSymptoms));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_SUB_SYMPTOMS_LIST_FOUND));
            }

            $responseJson->setObj($subSymptoms);
            $responseJson->sendSuccessResponse();
        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::SUB_SYMPTOMS_LIST_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::SUB_SYMPTOMS_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
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
        $responseJson = null;
        //dd($subSymptomId);

        try
        {
            $symptoms = $this->hospitalService->getSymptomsForSubSymptoms($subSymptomId);
            //dd($symptoms);

            if(!is_null($symptoms) && !empty($symptoms))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::SYMPTOMS_LIST_SUCCESS));
                $responseJson->setCount(sizeof($symptoms));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_SYMPTOMS_LIST_FOUND));
            }

            $responseJson->setObj($symptoms);
            $responseJson->sendSuccessResponse();
        }
        catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::SYMPTOMS_LIST_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        catch(Exception $exc)
        {
            //dd($exc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::SYMPTOMS_LIST_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
    }

    /**
     * Get personal history for the patient
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPersonalHistory($patientId)
    {
        $personalHistoryDetails = null;
        $responseJson = null;

        try
        {
            $personalHistoryDetails = $this->hospitalService->getPersonalHistory($patientId);
            //dd($personalHistoryDetails);

            if(!is_null($personalHistoryDetails) && !empty($personalHistoryDetails))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PERSONAL_HISTORY_SUCCESS));
                $responseJson->setCount(sizeof($personalHistoryDetails));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_PERSONAL_HISTORY_FOUND));
            }

            $responseJson->setObj($personalHistoryDetails);
            $responseJson->sendSuccessResponse();
        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.$hospitalExc->getUserErrorCode()));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        /*catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PERSONAL_HISTORY_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }*/
        catch(Exception $exc)
        {
            //dd($exc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PERSONAL_HISTORY_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
    }

    /**
     * Get patient past illness
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientPastIllness($patientId)
    {
        $pastIllness = null;
        $responseJson = null;

        try
        {
            $pastIllness = $this->hospitalService->getPatientPastIllness($patientId);
            //dd($pastIllness);

            if(!is_null($pastIllness) && !empty($pastIllness))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_PAST_ILLNESS_DETAILS_SUCCESS));
                $responseJson->setCount(sizeof($pastIllness));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_PATIENT_PAST_ILLNESS_DETAILS_FOUND));
            }

            $responseJson->setObj($pastIllness);
            $responseJson->sendSuccessResponse();
        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.$hospitalExc->getUserErrorCode()));
            $responseJson->sendErrorResponse($hospitalExc);
        }
        /*catch(HospitalException $hospitalExc)
        {
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_PAST_ILLNESS_DETAILS_ERROR));
            $responseJson->sendErrorResponse($hospitalExc);
        }*/
        catch(Exception $exc)
        {
            //dd($exc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_PAST_ILLNESS_DETAILS_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
    }

    /**
     * Get patient family illness
     * @param $patientId
     * @throws $hospitalException
     * @return array | null
     * @author Baskar
     */

    public function getPatientFamilyIllness($patientId)
    {
        $familyIllness = null;
        $responseJson = null;

        try
        {
            $familyIllness = $this->hospitalService->getPatientFamilyIllness($patientId);
            //dd($familyIllness);

            if(!is_null($familyIllness) && !empty($familyIllness))
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::PATIENT_FAMILY_ILLNESS_DETAILS_SUCCESS));
                $responseJson->setCount(sizeof($familyIllness));
            }
            else
            {
                $responseJson = new ResponsePrescription(ErrorEnum::SUCCESS, trans('messages.'.ErrorEnum::NO_PATIENT_FAMILY_ILLNESS_DETAILS_FOUND));
            }

            $responseJson->setObj($familyIllness);
            $responseJson->sendSuccessResponse();
        }
        catch(HospitalException $hospitalExc)
        {
            //dd($hospitalExc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.$hospitalExc->getUserErrorCode()));
            $responseJson->sendErrorResponse($hospitalExc);
        }
            /*catch(HospitalException $hospitalExc)
            {
                $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_PAST_ILLNESS_DETAILS_ERROR));
                $responseJson->sendErrorResponse($hospitalExc);
            }*/
        catch(Exception $exc)
        {
            //dd($exc);
            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_FAMILY_ILLNESS_DETAILS_ERROR));
            $responseJson->sendUnExpectedExpectionResponse($exc);
        }

        return $responseJson;
    }

    /*Symptom section -- End */
}
