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
}
