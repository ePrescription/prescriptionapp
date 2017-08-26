<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class MotionExamination extends Model
{
    protected $table = 'motion_examination';

    public function patients()
    {
        return $this->belongsToMany('App\User', 'patient_motion_examination', 'motion_examination_id', 'patient_id');
    }
}
