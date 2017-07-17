<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class GeneralExamination extends Model
{
    protected $table = 'general_examination';

    public function patients()
    {
        return $this->belongsToMany('App\User', 'patient_general_history', 'general_examination_id', 'patient_id');
    }
}
