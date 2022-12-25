<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyContract extends Model {

    protected $table = 'property_contract';

    public function property() {
        return $this->belongsTo('App\Models\Property');
    }

}
