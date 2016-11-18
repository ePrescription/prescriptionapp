<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class PatientPrescription extends Model
{
    protected $table = 'patient_prescription';

    public function patient()
    {
        return $this->belongsTo('App\User', 'patient_id');
    }

    public function prescriptiondetails()
    {
        return $this->hasMany('App\prescription\model\entities\PrescriptionDetails', 'patient_prescription_id');
    }
}
