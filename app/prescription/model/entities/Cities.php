<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    protected $table = 'cities';

    public function pharmacies()
    {
        return $this->hasMany('App\prescription\model\entities\Pharmacy', 'city');
    }
}
