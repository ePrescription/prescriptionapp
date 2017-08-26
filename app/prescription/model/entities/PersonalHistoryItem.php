<?php

namespace App\prescription\model\entities;

use Illuminate\Database\Eloquent\Model;

class PersonalHistoryItem extends Model
{
    protected $table = 'personal_history_item';

    public function personalhistory()
    {
        return $this->belongsTo('App\prescription\model\entities\PersonalHistory', 'personal_history_id');
    }

}
