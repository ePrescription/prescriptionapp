<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class Scans extends Model
{
    protected $table = 'scans';

    public function patients()
    {
        return $this->belongsToMany('App\User', 'patient_scan', 'scan_id', 'patient_id');
    }
}
