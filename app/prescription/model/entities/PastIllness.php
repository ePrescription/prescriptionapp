<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class PastIllness extends Model
{
    protected $table = 'past_illness';

    public function patients()
    {
        return $this->belongsToMany('App\User', 'patient_past_illness', 'past_illness_id', 'patient_id');
    }
}
