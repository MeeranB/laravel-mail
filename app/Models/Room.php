<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model {

    protected $table = 'room';

    public function property()
    {
        return $this->belongsTo('App\Models\Property');
    }

}
