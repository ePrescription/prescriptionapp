<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class PatientLabTests extends Model
{
    protected $table = 'patient_labtest';

    public function patient()
    {
        return $this->belongsTo('App\User', 'patient_id');
    }

    public function labtestdetails()
    {
        return $this->hasMany('App\prescription\model\entities\LabTestDetails', 'patient_labtest_id');
    }
}
