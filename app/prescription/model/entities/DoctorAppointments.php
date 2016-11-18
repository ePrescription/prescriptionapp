<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class DoctorAppointments extends Model
{
    protected $table = 'doctor_appointment';

    public function doctor()
    {
        return $this->belongsTo('App\User', 'doctor_id');
    }
}
