<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'patient';

    protected $fillable = array('name', 'address', 'city', 'country', 'pid', 'telephone',
        'email', 'relationship', 'patient_spouse_name', 'patient_photo', 'dob', 'age', 'place_of_birth', 'nationality', 'gender', 'married',
        'created_at', 'modified_at', 'created_by', 'updated_by');

    protected $guarded = array('id', 'patient_id');

    public function appuser()
    {
        return $this->belongsTo('App\User', 'patient_id');
    }

    /*public function hospital()
    {
        return $this->belongsToMany('App\prescription\model\entities\Hospital', 'hospital_patient', 'patient_id', 'hospital_id');
    }*/

    public function hospital()
    {
        return $this->belongsToMany('App\User', 'hospital_patient', 'hospital_id', 'patient_id');
    }
}
