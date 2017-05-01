<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = 'doctor';

    public function feereceipts()
    {
        return $this->hasMany('App\prescription\model\entities\FeeReceipt', 'doctor_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'doctor_id');
    }
}
