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
}
