<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyFacility extends Model {

    protected $table = 'property_facility';

    public function property() {
        return $this->belongsTo('App\Models\Property');
    }

    public function groupSetGroup() {
        return $this->belongsTo('App\Models\GroupSetGroup', 'group_id');
    }
}
