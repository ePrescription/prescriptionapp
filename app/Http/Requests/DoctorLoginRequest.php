<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class DoctorLoginRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        //return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        //return $this->all();
        //$rules['email'] = 'required | email | unique:users';
        $rules['email'] = 'required | email';
        $rules['password'] = 'required';

        return $rules;
    }
}
