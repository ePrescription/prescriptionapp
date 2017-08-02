<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable;
    //use Authorizable,
    use CanResetPassword;
    use EntrustUserTrait;
    //use HasRoles;

    protected $table = 'users';

    //use EntrustUserTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function hospital()
    {
        return $this->hasOne('App\prescription\model\entities\Hospital', 'hospital_id');
    }

    public function prescriptions()
    {
        return $this->hasMany('App\prescription\model\entities\PatientPrescription', 'patient_id');
    }

    public function labtests()
    {
        return $this->hasMany('App\prescription\model\entities\PatientLabTests', 'patient_id');
    }

    public function pharmacy()
    {
        return $this->hasOne('App\prescription\model\entities\Pharmacy', 'pharmacy_id');
    }

    public function lab()
    {
        return $this->hasOne('App\prescription\model\entities\Lab', 'lab_id');
    }

    public function patient()
    {
        return $this->hasOne('App\prescription\model\entities\Patient', 'patient_id');
    }

    public function appointments()
    {
        return $this->hasMany('App\prescription\model\entities\DoctorAppointments', 'doctor_id');
    }

    public function patienthospitals()
    {
        return $this->belongsToMany('App\prescription\model\entities\Patient', 'hospital_patient', 'patient_id', 'hospital_id')
            ->withPivot('created_by', 'updated_by')
            ->withTimestamps();
    }

    public function feereceipts()
    {
        return $this->hasMany('App\prescription\model\entities\FeeReceipt', 'doctor_id');
    }

    public function doctor()
    {
        return $this->hasOne('App\prescription\model\entities\Doctor', 'doctor_id');
    }

    public function personalhistory()
    {
        return $this->belongsToMany('App\prescription\model\entities\PersonalHistory',
            'patient_personal_history', 'patient_id', 'personal_history_id')
            ->withPivot('personal_history_item_id', 'is_value_set',
                'created_by', 'modified_by', 'created_at', 'updated_at');
    }

    public function patientgeneralexaminations()
    {
        return $this->belongsToMany('App\prescription\model\entities\GeneralExamination',
            'patient_general_examination', 'patient_id', 'general_examination_id')
            ->withPivot('general_examination_value', 'is_value_set',
                'created_by', 'modified_by', 'created_at', 'updated_at');
    }

    public function patientpastillness()
    {
        return $this->belongsToMany('App\prescription\model\entities\PastIllness',
            'patient_past_illness', 'patient_id', 'past_illness_id')
            ->withPivot('past_illness_name', 'is_value_set',
                'created_by', 'modified_by', 'created_at', 'updated_at');
    }

    public function patientfamilyillness()
    {
        return $this->belongsToMany('App\prescription\model\entities\FamilyIllness',
            'patient_family_illness', 'patient_id', 'family_illness_id')
            ->withPivot('family_illness_name', 'relation', 'is_value_set',
                'created_by', 'modified_by', 'created_at', 'updated_at');
    }

    public function patientpregnancy()
    {
        return $this->belongsToMany('App\prescription\model\entities\Pregnancy',
            'patient_pregnancy', 'patient_id', 'pregnancy_id')
            ->withPivot('pregnancy_value', 'pregnancy_date', 'is_value_set',
                'created_by', 'modified_by', 'created_at', 'updated_at');
    }

    public function patientscans()
    {
        return $this->belongsToMany('App\prescription\model\entities\Scans',
            'patient_scan', 'patient_id', 'scan_id')
            ->withPivot('scan_date', 'is_value_set',
                'created_by', 'modified_by', 'created_at', 'updated_at');
    }

    /*public function patientsymptoms()
    {
        return $this->belongsToMany('App\prescription\model\entities\Scans',
            'patient_scan', 'patient_id', 'scan_id')
            ->withPivot('scan_date', 'is_value_set',
                'created_by', 'modified_by', 'created_at', 'updated_at');
    }*/
}
