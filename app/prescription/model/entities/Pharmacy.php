<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    protected $table = 'pharmacy';

    protected $fillable = array('name', 'address', 'city', 'country', 'telephone', 'pharmacy_photo',
                'created_by', 'updated_by', 'created_at', 'updated_at');

    protected $guarded = array('id', 'pharmacy_id', 'phid', 'email');

    public function city()
    {
        return $this->hasOne('App\prescription\model\entities\Cities', 'city');
    }

    public function hospital()
    {
        return $this->belongsTo('App\User', 'pharmacy_id');
    }
}
