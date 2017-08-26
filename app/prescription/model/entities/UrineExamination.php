<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class UrineExamination extends Model
{
    protected $table = 'urine_examination';

    public function patients()
    {
        return $this->belongsToMany('App\User', 'patient_urine_examination', 'urine_examination_id', 'patient_id');
    }
}
