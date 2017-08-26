<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class PatientDrugHistory extends Model
{
    protected $table = 'patient_drug_history';

    public function patient()
    {
        return $this->belongsTo('App\User', 'patient_id');
    }
}
