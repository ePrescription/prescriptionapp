<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EditPatientProfileRequest extends Request
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
        $rules = [];

        $rules['name'] = 'required';
        $rules['address'] = 'required';
        $rules['dob'] = 'date_format:Y-m-d';
        $rules['doctorId'] = 'required';
        $rules['hospitalId'] = 'required';
        //$rules['age'] = 'required | numeric';
        //$rules['gender'] = 'required';

        return $rules;
    }
}
