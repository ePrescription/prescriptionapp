<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class FamilyIllness extends Model
{
    protected $table = 'family_illness';

    public function patients()
    {
        return $this->belongsToMany('App\User', 'patient_family_illness', 'family_illness_id', 'patient_id');
    }
}
