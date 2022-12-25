<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model {

    protected $table = 'country';

    public function guest() {
        return $this->hasOne('App\Models\Guest');
    }

}
