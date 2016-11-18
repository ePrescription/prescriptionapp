<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    protected $table = 'lab';

    protected $fillable = array('name', 'address', 'city', 'country', 'pincode', 'telephone', 'lab_photo',
        'created_by', 'updated_by', 'created_at', 'updated_at');

    protected $guarded = array('id', 'lab_id', 'lid', 'email');

    public function hospital()
    {
        return $this->belongsTo('App\User', 'lab_id');
    }
}
