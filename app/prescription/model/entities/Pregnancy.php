<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class Pregnancy extends Model
{
    protected $table = 'pregnancy';

    public function patients()
    {
        return $this->belongsToMany('App\User', 'patient_pregnancy', 'pregnancy_id', 'patient_id');
    }
}
