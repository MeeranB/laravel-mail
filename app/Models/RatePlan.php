<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatePlan extends Model {

    protected $table = 'rate_plan';

    public function property()
    {
        return $this->belongsTo('App\Models\Property');
    }

}
