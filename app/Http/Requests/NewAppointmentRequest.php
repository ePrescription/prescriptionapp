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
                $currentAppTime = $this->get('appointmentTime');

                $appDuration = strtotime("+30 minutes", strtotime($currentAppTime));

                $minutes = date('i', strtotime($currentAppTime));
                $hours = date('H', strtotime($currentAppTime));
                $min = $minutes - ($minutes % 30);

                if($min != 30)
                {
                    $min = date('i', $minutes - ($minutes % 30));
                }
                $lowestTime = $hours.":".$min;
                $upperTime = date('H:i', strtotime("+29 minutes", strtotime($lowestTime)));
                //$upperTime = date(""strtotime("+30 minutes", strtotime($lowestTime));

                //$lowerMinute = $minutes - 30;
                //$upperMinute = $minutes + 30;

                /*$data = array();
                $data['lower'] = $lowestTime;
                $data['upper'] = $upperTime;

                return json_encode($data);*/

                $query = DB::table('doctor_appointment as da')->where('da.doctor_id', $doctorId);
                $query->where('da.hospital_id', $hospitalId);
                $query->whereDate('da.appointment_date', '=', $appDate);
                /*$query->where(function($query) use($lowestTime, $upperTime)
                {
                    $query->where('da.appointment_time', $lowestTime);
                    $query->OrWhere('da.appointment_time', $upperTime);
                });*/
                //$query->where('da.appointment_time', $appDuration);
                $query->where('da.appointment_time', $lowestTime);
                $query->OrWhere('da.appointment_time', $upperTime);

                //$query->whereBetween('da.appointment_time', [$appTime, $appDuration]);

                //dd($query->toSql());
                $recCount = $query->count();

                //if(!is_null())

                //if()
                //$appTime = $appointments->appointment_time;

                //$minutes = date('i', strtotime($appTime));



                //return json_encode($data);

                /*if($currentAppTime >= $appTime && $currentAppTime <=  $appDuration)
                {
                    return true;
                }
                else
                {
                    return false;
                }*/


                /*$rec = $query->count();*/

                if($recCount == 0)
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
