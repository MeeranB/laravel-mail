<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model {

    protected $table = 'discount';

    public function property() {
        return $this->belongsTo('App\Models\Property');
    }

    public function group() {
        return $this->belongsTo('App\Models\Group');
    }

}
