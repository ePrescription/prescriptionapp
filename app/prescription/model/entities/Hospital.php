<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    protected $table = 'hospital';

    protected $fillable = array('hospital_name', 'address', 'city', 'country',
        'pin', 'telephone', 'email', 'website',
        'hospital_logo', 'hospital_photo', 'created_at', 'modified_at', 'created_by', 'updated_by');

    protected $guarded = array('id', 'hospital_id');

    public function user()
    {
        return $this->belongsTo('App\User', 'hospital_id');
    }

    /*public function patients()
    {
        return $this->belongsToMany('App\prescription\model\entities\Patient', 'hospital_patient', 'hospital_id', 'patient_id')
            ->withPivot('created_by', 'updated_by')
            ->withTimestamps();
    }*/

    /*public function patients()
    {
        return $this->belongsToMany('App\User', 'hospital_patient', 'hospital_id', 'patient_id');
    }*/
}
