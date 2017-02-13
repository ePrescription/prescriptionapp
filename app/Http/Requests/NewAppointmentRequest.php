<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Validation\Factory;
use Illuminate\Support\Facades\DB;

class NewAppointmentRequest extends BasePrescriptionRequest
{

    public function __construct(Factory $validationFactory)
    {

        $validationFactory->extend(
            'duplicate',
            function ($attribute, $value, $parameters) {
                $doctorId = $this->get('doctorId');
                $hospitalId = $this->get('hospitalId');
                $appDate = $this->get('appointmentDate');
                $appTime = $this->get('appointmentTime');

                $query = DB::table('doctor_appointment as da')->where('da.doctor_id', $doctorId);
                $query->where('da.hospital_id', $hospitalId);
                $query->whereDate('da.appointment_date', '=', $appDate);
                $query->where('da.appointment_time', $appTime);

                $rec = $query->count();

                if($rec == 0)
                {
                    return true;
                }
                else
                {
                    return false;
                }


            },
            'No appointment available for the requested time!!!'
        );

    }

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

        $rules['patientId'] = 'required';
        $rules['hospitalId'] = 'required';
        $rules['doctorId'] = 'required';
        //$rules['appointmentDate'] = 'required | date | after:' .date('Y-m-d') . '| date_format:Y-m-d';
        $rules['appointmentDate'] = 'required | date_format:Y-m-d';
        //$rules['appointmentTime'] = 'date_format: H:i:s | required | duplicate';
        //$rules['appointmentTime'] = ['required', 'regex:^(([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?)$^', 'duplicate'];


        //$rules['appointmentTime'] = ['required', 'regex:^([1-9]|1[012]):[0-5][0-9])?)$^', 'duplicate'];

        //Following is 24 hour format
        $rules['appointmentTime'] = ['required', 'regex:^(([0-1][0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?)$^', 'duplicate'];
        //$rules['appointmentTime'] = ['required', preg_match("/(1[012]|0[0-9]):([0-5][0-9])/", $this->get('appointmentTime')), 'duplicate'];

        //$rules['appointmentTime'] = ['required', 'regex:^/(1[012]|0[0-9]):([0-5][0-9])/^', 'duplicate'];

        return $rules;
    }
}
