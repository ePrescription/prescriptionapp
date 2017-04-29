<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class FeeReceipt extends Model
{
    protected $table = 'fee_receipt';

    public function doctor()
    {
        return $this->belongsTo('App\prescription\model\entities\Doctor', 'doctor_id');
    }
}
