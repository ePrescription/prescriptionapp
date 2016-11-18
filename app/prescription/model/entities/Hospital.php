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
}
