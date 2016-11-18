<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class LabTestDetails extends Model
{
    protected $table = 'labtest_details';

    public function patientlabtests()
    {
        return $this->belongsTo('App\prescription\model\entities\PatientLabTests', 'patient_labtest_id');
    }
}
