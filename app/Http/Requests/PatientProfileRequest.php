<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\prescription\common\ResponsePrescription;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\prescription\common\ResponseJson;
use App\prescription\utilities\ErrorEnum\ErrorEnum;

//class PatientProfileRequest extends Request
class PatientProfileRequest extends BasePrescriptionRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $profile = (object) $this->all();

        $rules = [];

        $rules['name'] = 'required';
        $rules['address'] = 'required';

        if($profile->patientId == 0)
        {
            $rules['email'] = 'email | unique:users,email';
        }
        $rules['dob'] = 'date_format:Y-m-d';
        $rules['doctorId'] = 'required';
        $rules['hospitalId'] = 'required';
        //$rules['age'] = 'required | numeric';
        //$rules['gender'] = 'required';

        return $rules;
    }

    /*public function wantsJson()
    {
        return true;
    }*/

    /*public function response(array $errors)
    {
        if (($this->ajax() && ! $this->pjax()) || $this->wantsJson()) {
            //return new JsonResponse($errors, 422);

            $responseJson = new ResponsePrescription(ErrorEnum::FAILURE, trans('messages.'.ErrorEnum::VALIDATION_ERRORS));
            $responseJson->setObj($errors);
            $responseJson->sendValidationErrors();

            return $responseJson;



        }

        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }*/
}
