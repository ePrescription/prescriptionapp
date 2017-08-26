<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class Ultrasound extends Model
{
    protected $table = 'ultra_sound';

    public function patients()
    {
        return $this->belongsToMany('App\User', 'patient_ultra_sound', 'ultra_sound_id', 'patient_id');
    }
}
