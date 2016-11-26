<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\prescription\common\ResponsePrescription;
use App\prescription\utilities\ErrorEnum\ErrorEnum;

abstract class BasePrescriptionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    /*public function authorize()
    {
        return false;
    }*/

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    /*public function rules()
    {
        return [
            //
        ];
    }*/

    public function wantsJson()
    {
        return true;
    }

    public function response(array $errors)
    {
        if (($this->ajax() && ! $this->pjax()) || $this->wantsJson()) {
            //return new JsonResponse($errors, 422);

            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::VALIDATION_ERRORS));
            $responseJson->setObj($errors);
            $responseJson->sendValidationErrors();

            return $responseJson;

            /*$jsonResponse = new JsonResponse($errors, 422);
            $responseJson = new ResponseJson(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::PATIENT_PROFILE_SAVE_SUCCESS));
            $responseJson->setObj($jsonResponse);
            return $responseJson;*/

        }

        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }
}
