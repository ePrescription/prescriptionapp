<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class PersonalHistory extends Model
{
    protected $table = 'personal_history';

    public function personalhistoryitems()
    {
        return $this->hasMany('App\prescription\model\entities\PersonalHistoryItem', 'personal_history_id');
    }

    public function patients()
    {
        return $this->belongsToMany('App\User', 'patient_personal_history', 'personal_history_id', 'patient_id');
    }
}
