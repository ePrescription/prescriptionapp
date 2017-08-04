<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class BloodExamination extends Model
{
    protected $table = 'blood_examination';

    public function patients()
    {
        return $this->belongsToMany('App\User', 'patient_blood_examination', 'blood_examination_id', 'patient_id');
    }
}
