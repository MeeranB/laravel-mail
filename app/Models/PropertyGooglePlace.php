<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyGooglePlace extends Model {

    protected $table = 'property_google_place';

    public function property() {
        return $this->belongsToOne('App\Models\Property');
    }

}
