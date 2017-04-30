<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class FeeReceipt extends Model
{
    protected $table = 'fee_receipt';

    protected $fillable = array('hospital_id', 'doctor_id', 'patient_id', 'fee',
         'created_at', 'updated_at', 'created_by', 'modified_by');

    protected $guarded = array('id');

    public function doctor()
    {
        return $this->belongsTo('App\prescription\model\entities\Doctor', 'doctor_id');
    }
}
