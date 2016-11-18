<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class PrescriptionDetails extends Model
{
    protected $table = 'prescription_details';

    public function patientprescription()
    {
        return $this->belongsTo('App\prescription\model\entities\PatientPrescription', 'patient_prescription_id');
    }
}
