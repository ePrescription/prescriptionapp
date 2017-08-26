<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class PatientSurgeries extends Model
{
    protected $table = 'patient_surgeries';

    public function patient()
    {
        return $this->belongsTo('App\User', 'patient_id');
    }
}
